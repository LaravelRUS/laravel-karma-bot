var gulp    = require('gulp');
var console = require('gulp-util');
var argv    = require('yargs').argv;
var debug   = require('gulp-debug');

/*
 |--------------------------------------------------------------------------
 |                              Всякие утилиты
 |--------------------------------------------------------------------------
 */
var utils = {
    concat:     require('gulp-concat'),
    sourcemaps: require('gulp-sourcemaps'),
    commonjs:   require('gulp-wrap-commonjs'),
    del:        require('del'),
    prefixes:   require('gulp-autoprefixer')
};

/*
 |--------------------------------------------------------------------------
 |        Минификаторы для стилей, скриптов, картинок и мб ещё чего
 |--------------------------------------------------------------------------
 */
var minify = {
    css:  require('gulp-minify-css'),
    js:   require('gulp-uglify'),
    gzip: require('gulp-gzip')
};

/*
 |--------------------------------------------------------------------------
 |                          Компиляторы разных языков
 |--------------------------------------------------------------------------
 */
var compilers = {
    coffee: require('gulp-coffee'),
    sass:   require('gulp-sass'),
    babel:  require('gulp-babel')
};

/*
 |--------------------------------------------------------------------------
 |                                Пути
 |--------------------------------------------------------------------------
 */
var path = {
    base:    'app/',
    storage: 'storage/framework/cache',
    publish: 'public/assets'
};


/*
 |--------------------------------------------------------------------------
 |                            Сам компилятор
 |--------------------------------------------------------------------------
 */
var Compiler = (function () {
    // Окружения
    Compiler.ENV_LOCAL      = 'local';
    Compiler.ENV_PRODUCTION = 'production';

    // Типы данных
    Compiler.EXT_STYLE  = 'css';
    Compiler.EXT_SCRIPT = 'js';

    /**
     * @param format Тип данных
     * @param env Окружение
     * @constructor
     */
    function Compiler(format, env) {
        this.storage      = path.storage + '/' + this.hash() + '/';
        this.format       = format || Compiler.EXT_SCRIPT;
        this.environment  = env || this.getEnvironment();
        this.streams      = [];
        this.files        = [];
        this.prependFiles = [];
    }

    /**
     * @returns {*}
     */
    Compiler.prototype.getEnvironment = function() {
        if (argv.production != null) {
            return argv.production;
        }
        return Compiler.ENV_LOCAL;
    };

    /**
     * Prepend files in result (shims as example)
     *
     * @param file
     * @returns {Compiler}
     */
    Compiler.prototype.prepend = function (file) {
        for (var i = 0; i < this.prependFiles; i++) {
            if (this.prependFiles[i] === file) {
                return this;
            }
        }

        this.prependFiles.push(file);

        return this;
    };

    /**
     * Simple function of random hash
     *
     * @returns {string}
     */
    Compiler.prototype.hash = function () {
        return Math.floor((1 + Math.random()) * 0x100)
            .toString(16);
    };

    /**
     * Добавить файл (с компилем)
     *
     * @param files Файл
     * @param compiler Коллбек, принимающий стрим, должен возвращать стрим
     * @param wrap
     * @returns {Compiler}
     */
    Compiler.prototype.add = function (files, compiler, wrap) {
        if (wrap == null) {
            wrap = true;
        }

        var name = 'id' + (this.streams.length + 1) + '_' +
            this.hash() + this.hash() + '.' + this.format;
        this.files.push(this.storage + name);

        var stream = gulp
            .src(files)
            .pipe(debug({title: 'add:'}))
            .on('error', function (error) {
                console.log('Build error:', error.message);
            })
            .pipe(utils.sourcemaps.init());

        // Inject compiler here
        if (compiler != null) {
            stream = compiler(stream);
        }

        // Inject CommonJS for scripts
        if (wrap && this.format === Compiler.EXT_SCRIPT) {
            stream = stream
                .pipe(utils.commonjs({
                    pathModifier: function (path) {
                        return path
                            .replace(/\\/g, '/')
                            .replace(/.*?\/javascripts\//g, '')
                            .replace(/\.js$/, '');
                    }
                }));
        }

        // Inject autoprefixer
        if (this.format === Compiler.EXT_STYLE) {
            stream = stream
                .pipe(utils.prefixes({
                    browsers: ['last 2 versions'],
                    cascade:  false
                }));
        }

        stream = stream
            .pipe(utils.concat(name))
            .pipe(utils.sourcemaps.write());

        this.streams.push(stream);

        return this;
    };

    /**
     * Сборка всего в одно
     *
     * @param outputName
     * @param callback
     * @returns {Compiler}
     */
    Compiler.prototype.build = function (outputName, callback) {
        var self    = this;
        var streams = this.streams.length;
        var current = 0;

        // On all streams was finish
        var finish = function () {
            var stream = gulp
                .src(self.prependFiles.concat(self.files), {
                    strict:     true,
                    allowEmpty: false
                })
                .pipe(debug({title: 'build:'}))
                .on('error', function (error) {
                    console.log('Error when merge result:', error.message);
                })
                .pipe(utils.sourcemaps.init({loadMaps: true}))
                .pipe(utils.concat(outputName));

            // Minify
            if (self.environment === Compiler.ENV_PRODUCTION) {
                if (this.format === Compiler.EXT_SCRIPT) {
                    stream = stream.pipe(minify.js())

                } else if (this.format === Compiler.EXT_STYLE) {
                    stream = stream.pipe(minify.css())
                }
            }

            stream = stream.pipe(utils.sourcemaps.write('./'));

            // Gzip
            if (self.environment === Compiler.ENV_PRODUCTION) {
                stream = stream
                    .pipe(gulp.dest(path.publish))
                    .pipe(minify.gzip());
            }

            stream = stream
                .pipe(gulp.dest(path.publish))
                .on('finish', function () {
                    if (callback != null) {
                        callback(path.publish + '/' + outputName);
                    }
                });

            return stream;
        };

        /**
         * Merge stream builds
         */
        this.streams.forEach(function (stream, index) {
            stream
                .pipe(gulp.dest(self.storage))
                .on('finish', function () {
                    current++;
                    if (streams === current) {
                        return finish();
                    }
                });
        });


        return this;
    };

    /**
     * @param files
     * @param wrap
     * @returns {*}
     */
    Compiler.prototype.js = function (files, wrap) {
        return this.add(files, function (stream) {
            return stream;
        }, wrap);
    };

    /**
     * @param files
     * @param wrap
     * @returns {*}
     */
    Compiler.prototype.coffee = function (files, wrap) {
        return this.add(files, function (stream) {
            return stream.pipe(
                compilers.coffee({bare: true}).on('error', function (error) {
                    console.log('Coffee Error:', error.message);
                })
            );
        }, wrap);
    };

    /**
     * @param files
     * @param wrap
     * @param optional
     * @returns {Compiler}
     */
    Compiler.prototype.babel = function (files, wrap, optional) {
        this.prepend(require.resolve('babel-core/browser-polyfill'));

        return this.add(files, function (stream) {
            return stream.pipe(
                compilers.babel({
                    modules:  'common',
                    optional: optional
                }).on('error', function (error) {
                    console.log('Babel Error:', error.message);
                })
            );
        }, wrap);
    };

    /**
     * @param files
     * @param wrap
     * @returns {*}
     */
    Compiler.prototype.sass = function (files, wrap) {
        return this.add(files, function (stream) {
            return stream.pipe(
                compilers.sass().on('error', function (error) {
                    console.log('Sass Error:', error.message);
                })
            );
        }, wrap);
    };

    return Compiler;
})();


/* ============================= *
 *         TASKS (EXAMPLE)       *
 * ============================= */

gulp.task('make', function () {

});
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
     * @param options Окружение
     * @constructor
     */
    function Compiler(format, options) {
        this.options = {
            // Environment
            env: options.env || Compiler.ENV_LOCAL,

            // Storage path
            storage: (options.storage || 'storage') + '/' + this.hash() + '/',

            // Public path
            publish: options.publish || 'out',

            // Default namespace (CommonJS default path)
            base: options.commonJsBase || 'app',

            // File paths
            paths: options.paths || []
        };

        this.format       = format || Compiler.EXT_SCRIPT;
        this.streams      = [];
        this.files        = [];
        this.prependFiles = [];
    }

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
        return Math.floor((1 + Math.random()) * 0x10)
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
        var self = this;

        if (typeof wrap === 'undefined' || wrap == null) {
            wrap = true;
        }

        if (typeof files === 'undefined' || files == null) {
            throw new Error('Files not exists');
        }

        if (!(files instanceof Array)) {
            files = [files];
        }


        // Appends base paths
        var newFileList = files.slice();
        for (var i = 0; i < this.options.paths.length; i++) {
            var path = this.options.paths[i];

            for (var j = 0; j < files.length; j++) {
                newFileList.push(path + '/' + files[j]);
            }
        }
        files = newFileList;


        var name = 'id' + (this.streams.length + 1) + '_' +
            this.hash() + this.hash() + '.' + this.format;
        this.files.push(this.options.storage + name);

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
                        var commonJsBase = new RegExp('.*?\/' + (self.options.base || 'src') + '\/', 'g');
                        return path
                            .replace(/\\/g, '/')
                            .replace(commonJsBase, '')
                            .replace(/\.js|\.es6|\.jsx$/, '');
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

        if (typeof callback === 'undefined' || !(callback instanceof Function)) {
            callback = function (output) {
                console.log('File was be published at:', output);
            };
        }

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
            if (self.options.env === Compiler.ENV_PRODUCTION) {
                if (self.format === Compiler.EXT_SCRIPT) {
                    stream = stream.pipe(minify.js());

                } else if (self.format === Compiler.EXT_STYLE) {
                    stream = stream.pipe(minify.css());
                }
            }

            stream = stream.pipe(utils.sourcemaps.write('./'));

            // Gzip
            if (self.options.env === Compiler.ENV_PRODUCTION) {
                stream = stream
                    .pipe(gulp.dest(self.options.publish))
                    .pipe(minify.gzip());
            }

            stream = stream
                .pipe(gulp.dest(self.options.publish))
                .on('finish', function () {
                    if (callback != null) {
                        callback(self.options.publish + '/' + outputName);
                    }
                });

            return stream;
        };

        /**
         * Merge stream builds
         */
        this.streams.forEach(function (stream, index) {
            stream
                .pipe(gulp.dest(self.options.storage))
                .on('finish', function () {
                    current++;
                    if (streams === current) {
                        return finish();
                    }
                });
        });


        return this;
    };


    /*
     |--------------------------------------------------------------------------
     |                                  COMPILERS
     |--------------------------------------------------------------------------
     */


    /**
     * @param files
     * @param commonJsWrap
     * @returns {*}
     */
    Compiler.prototype.js = function (files, commonJsWrap) {
        return this.add(files, function (stream) {
            return stream;
        }, commonJsWrap || false);
    };

    /**
     * @param files
     * @returns {*}
     */
    Compiler.prototype.css = function (files) {
        return this.add(files, function (stream) {
            return stream;
        }, false);
    };

    /**
     * @param files
     * @param commonJsWrap
     * @returns {*}
     */
    Compiler.prototype.coffee = function (files, commonJsWrap) {
        return this.add(files, function (stream) {
            var coffee = require('gulp-coffee');

            return stream.pipe(
                coffee({bare: true}).on('error', function (error) {
                    console.log('Coffee Error:', error.message);
                })
            );
        }, commonJsWrap || false);
    };

    /**
     * @param files
     * @param options
     * @returns {Compiler}
     */
    Compiler.prototype.babel = function (files, options) {
        options = {
            modules:   (options.modules || 'common'),
            optional:  (options.optional || []),
            blacklist: (options.blacklist || []),
            plugins:   (options.plugins || []),
            loose:     (options.loose || [])
        };

        if (this.options.env === Compiler.ENV_PRODUCTION) {
            options.optional.push('minification.removeConsole');
            options.optional.push('minification.removeDebugger');
        }

        return this.add(files, function (stream) {
            var babel = require('gulp-babel');

            return stream.pipe(
                babel(options).on('error', function (error) {
                    console.log('Babel Error:', error.message, '[opt]: ', options);
                })
            );
        }, options.modules === 'common');
    };

    /**
     * @param files
     * @returns {*}
     */
    Compiler.prototype.sass = function (files) {
        return this.add(files, function (stream) {
            var sass = require('gulp-sass');

            return stream.pipe(
                sass().on('error', function (error) {
                    console.log('Sass Error:', error.message);
                })
            );
        }, false);
    };

    /**
     * @param files
     * @returns {*}
     */
    Compiler.prototype.less = function (files) {
        return this.add(files, function (stream) {
            var less = require('gulp-less');

            return stream.pipe(
                less({}).on('error', function (error) {
                    console.log('Less Error:', error.message);
                })
            );
        }, false);
    };

    return Compiler;
})();


/* ============================= *
 *         TASKS (EXAMPLE)       *
 * ============================= */

// Compiler options
var options = {
    env:          Compiler.ENV_PRODUCTION,
    storage:      'storage/assets',
    publish:      'public/assets',
    commonJsBase: 'javascripts',
    paths:        [
        'resources/stylesheets',
        'resources/javascripts'
    ]
};

// Example babel options
var babel = {
    optional: [
        'es7.decorators',
        'es7.classProperties',
        'es7.objectRestSpread',
        'es7.functionBind',
        'es7.trailingFunctionCommas'
    ],
    loose:    [
        'es6.classes'
    ]
};


gulp.task('scripts', function () {
    var compiler = new Compiler(Compiler.EXT_SCRIPT, options);

    compiler = compiler.js([
        // Babel runtime
        require.resolve('babel-core/browser-polyfill'),
        // CommonJS core for browsers
        require.resolve('commonjs-require/commonjs-require'),
        // KnockoutJS
        require.resolve('knockout/build/output/knockout-latest'),
        // Jquery
        require.resolve('jquery/dist/jquery')
    ]);

    compiler = compiler.babel([
        'resources/javascripts/**/*.js'
    ], babel);

    compiler.build('app.js', function (output) {
        console.log('File was be published at:', output);
    });
});


gulp.task('styles', function () {
    var compiler = new Compiler(Compiler.EXT_STYLE, options);

    compiler = compiler.sass('layout.scss');

    compiler.build('app.css');
});


gulp.task('default', ['scripts', 'styles'], function () {

});
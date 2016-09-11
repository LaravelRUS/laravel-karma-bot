var gulp    = require('gulp');
var builder = require('lightweb-builder').default;


gulp.task('scripts', function() {
    (new builder)
        //.withGzip()
        //.withMinify()
        .withPolyfill()
        .withCommonJs()
        .withSourceMaps()
        .then.js(require.resolve('knockout/build/output/knockout-latest'))
        .then.js(require.resolve('knockout-punches/knockout.punches'))
        .then.js('resources/javascripts/vendor/*.js')
        .then.es7(function(compiler) {
            compiler
                .plugin('syntax-flow')
                .plugin('transform-flow-strip-types')
                .namespace('/')
                .path('resources/javascripts/app/');
        })
        .build('./public/assets/app.js');
});


gulp.task('styles', function () {
    (new builder)
        //.withGzip()
        //.withMinify()
        .withSourceMaps()
        .scss(function(compiler) {
            compiler
                .file('resources/stylesheets/layout.scss')
                .autoPrefix();
        })
        .build('./public/assets/app.css');
});


gulp.task('default', ['scripts', 'styles'], function () {

});
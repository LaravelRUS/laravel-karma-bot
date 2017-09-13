var gulp    = require('gulp');
var builder = require('lightweb-builder').default;


gulp.task('scripts', function() {
    (new builder)
        .withGzip()
        .withMinify()
        .withPolyfill()
        .withCommonJs()
        .withSourceMaps()
        .then.js(function(compiler) {
            compiler
                .file(require.resolve('knockout/build/output/knockout-latest'))
                .file(require.resolve('jquery/dist/jquery'))
        })
        .then.es7(function(compiler) {
            compiler
                .plugin('syntax-flow')
                .plugin('transform-flow-strip-types')
                .namespace('/')
                .path('resources/javascripts/');
        })
        .build('./public/assets/app.js');
});


gulp.task('styles', function () {
    (new builder)
        .withGzip()
        .withMinify()
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
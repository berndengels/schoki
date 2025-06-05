const mix = require('laravel-mix');
//const webpack = require('webpack');
mix.webpackConfig({
    target: 'node',
    resolve: {
        alias: {
            jquery: "jquery/src/jquery"
        }
    },
    externals: {
        canvas: "commonjs canvas" // Important (2)
    }
});

mix.autoload({
        'jquery': ['jQuery', '$']
    })
    .setPublicPath('public')
    .js('resources/js/app.js', 'js/app.js')
    .js('resources/js/app-admin.js', 'js/app-admin.js')
    .js('resources/js/admin.js', 'js/admin.js')
    .sass('resources/sass/app-admin.scss', 'css/app-admin.css')
    .sass('resources/sass/red.scss', 'css/red.css')
    .sass('resources/sass/dark.scss', 'css/dark.css')
    .sass('resources/sass/tiny.scss', 'css/tiny.css')
    .copy('resources/fonts', 'public/fonts')
    .copy('resources/vendor/calendar/zabuto_calendar.min.css', 'public/vendor/calendar/css')
    .copy('resources/vendor/calendar/zabuto_calendar.min.js', 'public/vendor/calendar/js')
    .copy('node_modules/bootstrap-datepicker/dist', 'public/vendor/bootstrap-datepicker')
    .copy('node_modules/dropzone/dist/min/dropzone.min.js', 'public/vendor/dropzone/js')
    .copy('node_modules/dropzone/dist/min/dropzone.min.css', 'public/vendor/dropzone/css')
    .copy('node_modules/jstree/dist/jstree.min.js', 'public/vendor/jstree/js')
    .copy('node_modules/jstree/dist/jstree.js', 'public/vendor/jstree/js')
    .copy('node_modules/jstree/dist/themes', 'public/vendor/jstree/css')
    .copy('node_modules/cropperjs/dist/cropper.css', 'public/vendor/cropperjs/css')
    .copy('node_modules/tinymce', 'public/vendor/tinymce')
    .copy('resources/vendor/tinymce/plugins/responsivefilemanager', 'public/vendor/tinymce/plugins/responsivefilemanager')
    .copy('resources/vendor/tinymce/filemanager', 'public/vendor/filemanager')
    .copy('node_modules/leaflet/dist', 'public/vendor/leaflet')
;

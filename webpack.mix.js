const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/user.js', 'public/js/user.js')
   .js('resources/js/schedule.js', 'public/js/schedule.js')
   .js('resources/js/calendar.js', 'public/js/calendar.js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/user.scss', 'public/css/user.css')
   .sass('resources/sass/schedule.scss', 'public/css/schedule.css')
   .sourceMaps();

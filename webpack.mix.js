require('dotenv').config();
const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/bootstrap.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/bootstrap.scss', 'public/css')
   .copyDirectory('resources/assets/files', 'public');

if (mix.config.inProduction) {
    mix.version().disableNotifications();
}

else {
    mix.sourceMaps().browserSync(process.env.APP_DEV_URL);
}

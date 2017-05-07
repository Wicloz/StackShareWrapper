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

mix.js('resources/assets/js/app.js', 'public/js').extract([
     'vue',
     'jquery',
     'axios',
     'lodash',
     'bootstrap-sass',
   ])
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/bootstrap.scss', 'public/css')
   .copyDirectory('resources/assets/files/js', 'public/js')
   .copyDirectory('resources/assets/files/css', 'public/css');

if (mix.config.inProduction) {
  mix.version().disableNotifications();
}

else {
  mix.sourceMaps().browserSync(process.env.APP_URL);
}

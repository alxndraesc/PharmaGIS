const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .styles([
      'resources/css/bootstrap.min.css'
   ], 'public/css/vendor.css')
   .version();

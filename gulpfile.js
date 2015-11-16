var elixir = require('laravel-elixir');

elixir(function (mix) {
  mix
    .sass('app.scss')
    .scripts([
      '../vendor/jquery/dist/jquery.js',
      '../vendor/bootstrap-sass/assets/javascripts/bootstrap.js',
      '../vendor/google-code-prettify/src/run_prettify.js',
      'app.js'
    ], 'public/js/app.js')
    .version([
      'css/app.css',
      'js/app.js'
    ])
    .copy("resources/assets/vendor/font-awesome/fonts", "public/build/fonts");
    //.browserSync({proxy: 'localhost:8000'});
});

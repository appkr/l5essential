var elixir = require('laravel-elixir');

elixir(function (mix) {
  mix.sass('app.scss', 'resources/assets/css')
    .styles([
      'app.css',
      '../vendor/dropzone/dist/dropzone.css',
      '../vendor/earthsong.css',
    ], 'public/css/app.css');

  mix.scripts([
    '../vendor/jquery/dist/jquery.js',
    '../vendor/bootstrap-sass/assets/javascripts/bootstrap.js',
    '../vendor/fastclick/lib/fastclick.js',
    '../vendor/select2/dist/js/select2.js',
    '../vendor/dropzone/dist/dropzone.js',
    '../vendor/tabby/jquery.textarea.js',
    '../vendor/autosize/dist/autosize.js',
    '../vendor/highlightjs/highlight.pack.js',
    '../vendor/marked/lib/marked.js',
    'app.js'
  ], 'public/js/app.js');

  mix.version([
    'css/app.css',
    'js/app.js'
  ]);

    /* font files are static, so doesn't need to be copied every gulp run.
    //mix.copy("resources/assets/vendor/font-awesome/fonts", "public/build/fonts");

    /* To activate Browser Sync, uncomment and run $ gulp watch */
    //mix.browserSync();
});

var elixir = require('laravel-elixir');

elixir(function (mix) {
  mix
    .sass('app.scss', 'resources/assets/css')
    .styles([
      'app.css',
      '../vendor/dropzone/dist/dropzone.css',
      '../vendor/earthsong.css',
    ], 'public/css/app.css')
    .scripts([
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
    ], 'public/js/app.js')
    .version([
      'css/app.css',
      'js/app.js'
    ])
    .copy("resources/assets/vendor/font-awesome/fonts", "public/build/fonts");
    //.browserSync({proxy: 'localhost:8000'});
});

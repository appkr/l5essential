(function() {

  var App = {};

  App.init = function() {
    this._bind = function(fn, me) {
      return function() {
        return fn.apply(me, arguments);
      };
    };

    this.registerGlobals();
    this.addListeners();
    this.handleTextareas();
    this.manipulateUi();

    /* Activate syntax highlight.
     This will affect code blocks right after the page renders */
    hljs.initHighlightingOnLoad();
  };

  App.registerGlobals = function() {
    window.csrfToken = $('meta[name="csrf-token"]').attr('content');
    window.routeName = $('meta[name="route"]').attr('content');

    /* Set Ajax request header.
     Document can be found at http://laravel.com/docs/5.1/routing#csrf-x-csrf-token */
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': window.csrfToken
      }
    });
  };

  App.addListeners = function() {
    $('#back-to-top').on("click", function () {
      $('body,html').animate({
        scrollTop: 0
      }, 800);

      return false;
    });

    /* Modal window for Markdown Cheatsheet */
    $("#md-caller").on("click", function(e) {
      e.preventDefault();
      $("#md-modal").modal();

      return false;
    });

    /* Slide menu effect on a small device */
    $("div.nav__forum > a").on("click", function() {
      $(".sidebar__forum").slideToggle("fast");
      $('body,html').animate({ scrollTop: 0 }, "fast");
    });

    $("div.nav__lessons > a").on("click", function() {
      $(".sidebar__lessons").slideToggle("fast");
      $('body,html').animate({ scrollTop: 0 }, "fast");
    });

    /* Activate Fastclick */
    $(window).on("load", function() {
      FastClick.attach(document.body);
    });

    /* "Back to top" button */
    $(window).on("scroll", function () {
      var scrollPos = $(window).scrollTop();
      var boxLanding = $('.box__landing');

      if (scrollPos > 50) {
        $('#back-to-top').fadeIn();
      } else {
        $('#back-to-top').fadeOut();
      }

      if (boxLanding.length) {
        var objHeight = boxLanding.height();

        boxLanding.css({
          'opacity': 1 - (scrollPos / objHeight / 4)
        });
      }
    });
  };

  App.handleTextareas = function() {
    var textAreas = $('textarea');

    if (textAreas.length) {
      /* Activate Tabby on every textarea element */
      textAreas.tabby({tabString: '    '});

      /* Auto expand textarea size */
      autosize(textAreas);

      textAreas.on("focus", function (e) {
        // Show preview pane when a textarea is in focus
        var el = $(this).siblings("div.preview__forum").first();

        if (! el.html().length) {
          el.html("Preview will be shown here...");
        }

        el.show();
      });

      textAreas.on("keyup", function(e) {
        // Register 'keyup' event handler
        var self = $(this),
            content = self.val(),
            previewEl = self.siblings("div.preview__forum").first();

        // Compile textarea content
        var compiled = marked(content, {
          renderer: new marked.Renderer(),
          gfm: true,
          tables: true,
          breaks: true,
          pedantic: false,
          sanitize: true,
          smartLists: true,
          smartypants: false
        });

        // Fill preview container with compiled content
        previewEl.html(compiled);
        // Add syntax highlight on the preview content
        previewEl.find('pre code').each(function(i, block) {
          hljs.highlightBlock(block)
        });
      }).trigger("keyup");
    }
  };

  App.manipulateUi = function() {
    /* At the time of page loading, remove any element having flash-message class in 5 secs */
    if($(".flash-message")) {
      $(".flash-message").delay(5000).fadeOut();
    }

    if ($("#flash-overlay-modal")) {
      $("#flash-overlay-modal").modal();
    }

    /* Center image in the html which was compiled from markdown */
    $(".container__forum article>p>img, .container__lessons article>p>img").closest("p").addClass("text-center");

  };

  $(function() {
    return App.init();
  });

}).apply(this);

/* Global Helper Functions */

/* Generate flash message from javascript */
function flash(type, msg, delay) {
  var el = $("div.js-flash-message");

  if (el) {
    el.remove();
  }

  $("<div></div>", {
    "class": "alert alert-" + type + " alert-dismissible js-flash-message",
    "html": '<button type="button" class="close" data-dismiss="alert">'
    + '<span aria-hidden="true">&times;</span>'
    + '<span class="sr-only">Close</span></button>' + msg
  }).appendTo($(".container").first());

  $("div.js-flash-message").fadeIn("fast").delay(delay || 5000).fadeOut("fast");
}

/* Reload page */
function reload(interval) {
  setTimeout(function () {
    window.location.reload(true);
  }, interval || 5000);
}

/* Random background image for landing page to give fun to visitors */
//var images = ["459033.jpg", "378663.jpg", "413890.jpg", "419042.jpg", "510930.jpg"],
//    randomIndex = Math.floor(Math.random() * images.length),
//    pickedImage = images[randomIndex];
//$("#laracroft").css({"background-image": "url(/images/" + pickedImage + ")"});
//$(".credit").find("a").first().attr("href", "http://wall.alphacoders.com/big.php?i=" + pickedImage.substring(0, 6));

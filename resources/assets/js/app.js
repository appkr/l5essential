var csrfToken = $('meta[name="csrf-token"]').attr('content'),
  routeName = $('meta[name="route"]').attr('content'),
  textAreas = $('textarea');

/* Global Settings */

/* Activate Fastclick */
window.addEventListener('load', function() {
  FastClick.attach(document.body);
}, false);

/* Set Ajax request header.
 Document can be found at http://laravel.com/docs/5.1/routing#csrf-x-csrf-token */
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': csrfToken
  }
});

/* Layouts Related */

if (textAreas.length) {
  /* Activate Tabby on every textarea element */
  textAreas.tabby({tabString: '    '});

  /* Auto expand textarea size */
  autosize(textAreas);

  textAreas.on("focus", function (e) {
    // Show preview pane when a textarea is in focus
    $(this).siblings("div.preview__forum").first().show();
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

/* Activate syntax highlight.
   This will affect code blocks right after the page renders */
hljs.initHighlightingOnLoad();

/* At the time of page loading, remove any element having flash-message class in 5 secs */
if($(".flash-message")) {
  $(".flash-message").delay(5000).fadeOut();
}

/* Center image in the html which was compiled from markdown */
$(".container__forum article>p>img, .container__documents article>p>img").closest("p").addClass("text-center");

/* Decorations */

/* "Back to top" button */
$(window).scroll(function () {
  if ($(this).scrollTop() > 50) {
    $('#back-to-top').fadeIn();
  } else {
    $('#back-to-top').fadeOut();
  }
});

$('#back-to-top').click(function () {
  $('#back-to-top').tooltip('hide');
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

/* Helper Functions */

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

/* Slide menu effect on a small device */
$("div.nav__forum > a").on("click", function() {
  $(".sidebar__forum").slideToggle("fast");
  $('body,html').animate({ scrollTop: 0 }, "fast");
});

$("div.nav__documents > a").on("click", function() {
  $(".sidebar__documents").slideToggle("fast");
  $('body,html').animate({ scrollTop: 0 }, "fast");
});


/* Reload page */
function reload(interval) {
  setTimeout(function () {
    window.location.reload(true);
  }, interval || 5000);
}
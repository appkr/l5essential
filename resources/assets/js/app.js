/* Set Ajax request header */
/* Document can be found at http://laravel.com/docs/5.1/routing#csrf-x-csrf-token */
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

/* add class for Syntax Highlighting */
$("pre").addClass("prettyprint");

/* At the time of page loading, remove any element having flash-message class in 5 secs */
if($(".flash-message")) {
  $(".flash-message").delay(5000).fadeOut();
}

/* Center image in the html which was compiled from markdown */
$(".container__forum article>p>img, .container__documents article>p>img").closest("p").addClass("text-center");

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
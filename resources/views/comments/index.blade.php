<div class="container__forum">
  <a href="#" class="help-block pull-right hidden-xs" id="md-caller">
    <small>{!! icon('preview') !!} Markdown Cheatsheet</small>
  </a>
  <h4>{!! icon('comments') !!} {{ trans('forum.title_comments') }}</h4>

  @if($currentUser)
    @include('comments.partial.create')
  @endif

  @forelse($comments as $comment)
    @include('comments.partial.comment', ['parentId' => $comment->id])
  @empty
  @endforelse
</div>

@section('style')
  <style>
    div.media__create:not(:first-child),
    div.media__edit {
      display: none;
    }
  </style>
@stop

@section('script')
  @parent
  <script>
    $("button.btn__reply").on("click", function(e) {
      // Toggle reply form
      var el__create = $(this).closest(".media__item").find(".media__create").first(),
          el__edit = $(this).closest(".media__item").find(".media__edit").first();

      el__edit.hide("fast");
      el__create.toggle("fast").end().find('textarea').focus();
    });

    $("a.btn__edit").on("click", function(e) {
      // Toggle edit form
      var el__create = $(this).closest(".media__item").find(".media__create").first(),
          el__edit = $(this).closest(".media__item").find(".media__edit").first();

      el__create.hide("fast");
      el__edit.toggle("fast").end().find('textarea').first().focus();
    });

    $("a.btn__delete").on("click", function(e) {
      // Make a delete request to the server
      var commentId = $(this).closest(".media__item").data("id");

      if (confirm("Are you sure to delete this comment?")) {
        $.ajax({
          type: "POST",
          url: "/comments/" + commentId,
          data: {
            _method: "DELETE"
          }
        }).success(function() {
          flash('success', 'Deleted ! The page will reload in 3 secs.', 2500);
          reload(3000);
        });
      }
    });

    $("button.btn__pick").on("click", function(e) {
      // Update Best Answer against the Article model
      var articleId = $("#article__article").data("id"),
          commentId = $(this).closest(".media__item").data("id");

      if (confirm("Are you sure to select this comment as the 'Best'?")) {
        $.ajax({
          type: "POST",
          url: "/articles/" + articleId + "/pick",
          data: {
            _method: "PUT",
            solution_id: commentId
          }
        }).success(function() {
          flash('success', 'Updated ! The page will reload in 3 secs.', 2500);
          reload(3000);
        });
      }
    });

    $("#md-modal").on("click", function(e) {
      // Make an overlay, explaining markdown syntax
      e.preventDefault();
      $("#md-modal").modal();
      return false;
    });
  </script>
@stop
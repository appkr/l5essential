<div class="container__forum">
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
  <script>
    $("button.btn__reply").on("click", function(e) {
      var el__create = $(this).closest(".media__item").find(".media__create").first(),
          el__edit = $(this).closest(".media__item").find(".media__edit").first();

      el__edit.hide("fast");
      el__create.toggle("fast").end().find('textarea').focus();
    });

    $("a.btn__edit").on("click", function(e) {
      var el__create = $(this).closest(".media__item").find(".media__create").first(),
          el__edit = $(this).closest(".media__item").find(".media__edit").first();

      el__create.hide("fast");
      el__edit.toggle("fast").end().find('textarea').first().focus();
    });

    $("a.btn__delete").on("click", function(e) {
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
        })
      }
    });
  </script>
@stop
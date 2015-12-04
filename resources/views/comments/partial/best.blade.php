<div class="panel panel-info">
  <div class="panel-heading">Best Answer</div>
  <div class="panel-body">
    <div class="media" data-id="{{ $comment->id }}">

      @include('users.partial.avatar', ['user' =>  $comment->author])

      <div class="media-body">
        <h4 class="media-heading">
          <a href="{{ gravatar_profile_url($comment->author->email) }}">
            {{ $comment->author->name }}
          </a>
          <small>
            {{ $comment->created_at->diffForHumans() }}
          </small>
        </h4>
        <p>{!! markdown($comment->content) !!}</p>
      </div>
    </div>
  </div>
</div>
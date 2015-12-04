<div class="media media__item" data-id="{{ $comment->id }}">

  @include('users.partial.avatar', ['user' =>  $comment->author])

  <div class="media-body">

    @if($currentUser and ($comment->isAuthor() or $currentUser->isAdmin()))
      @include('comments.partial.control')
    @endif

    <h4 class="media-heading">
      <a href="{{ gravatar_profile_url($comment->author->email) }}">
        {{ $comment->author->name }}
      </a>
      <small>
        {{ $comment->created_at->diffForHumans() }}
      </small>
    </h4>

    <p>{!! markdown($comment->content) !!}</p>

    @if ($currentUser)
    <p class="text-right" style="margin-top: 1rem;">
      @if (! $solved && $articleOwner)
        <button type="button" class="btn btn-default btn-sm btn__pick" title="Pick as the Best Answer">
          {!! icon('pick', false) !!}
        </button>
      @endif
      <button type="button" class="btn btn-info btn-sm btn__reply">
        {!! icon('reply') !!} Reply
      </button>
    </p>
    @endif

    @if($currentUser and ($comment->isAuthor() or $currentUser->isAdmin()))
      @include('comments.partial.edit')
    @endif

    @if($currentUser)
      @include('comments.partial.create', ['parentId' => $comment->id])
    @endif

    @forelse ($comment->replies as $reply)
      @include('comments.partial.comment', ['comment'  => $reply])
    @empty
    @endforelse
  </div>
</div>
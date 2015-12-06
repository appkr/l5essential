@if ($isTrashed and ! $hasChild)
  <!-- A trashed item but has no child -->
@elseif ($isTrashed and $hasChild)
  <div class="media media__item" data-id="{{ $comment->id }}" style="{{ $isReply ? 'margin-bottom:0;' : '' }}">
    @include('users.partial.avatar')

    <div class="media-body @if (! $isReply) {{"border__item"}} @endif">
      <h4 class="media-heading">
        <span class="text-muted">???</span>
        <small>
          {{ $comment->deleted_at->diffForHumans() }}
        </small>
      </h4>
      <p class="text-danger">{{ trans('forum.deleted_comment') }}</p>

      @forelse ($comment->replies as $reply)
        @include('comments.partial.comment', [
          'comment'   => $reply,
          'isReply'   => true,
          'hasChild'  => count($reply->replies),
          'isTrashed' => $reply->trashed()
        ])
      @empty
      @endforelse
    </div>
  </div>
@else
  <div class="media media__item" data-id="{{ $comment->id }}" style="{{ $isReply ? 'margin-bottom:0;' : '' }}">
    @include('users.partial.avatar', ['user' =>  $comment->author])

    <div class="media-body @if (! $isReply) {{"border__item"}} @endif">

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
        <div class="row">
          <div class="col-xs-6">
            <div class="btn-group" role="group">
              <?php $voted = $comment->votes->contains('user_id', $currentUser->id); ?>
              <button type="button" class="btn btn-default btn-sm btn__vote" data-vote="up" title="Vote up" @if ($voted) {{ 'disabled="disabled"' }} @endif>
                {!! icon('up', false) !!} <span>{{ $comment->up_count }}</span>
              </button>
              <button type="button" class="btn btn-default btn-sm btn__vote" data-vote="down" title="Vote down" @if ($voted) {{ 'disabled="disabled"' }} @endif>
                {!! icon('down', false) !!} <span>{{ $comment->down_count }}</span>
              </button>
            </div>
          </div>
          <div class="col-xs-6 text-right">
            @if (! $solved && $owner)
              <button type="button" class="btn btn-default btn-sm btn__pick" title="{{ trans('forum.msg_pick_help') }}">
                {!! icon('check', false) !!}
              </button>
            @endif
            <button type="button" class="btn btn-info btn-sm btn__reply">
              {!! icon('reply') !!} {{ trans('common.reply') }}
            </button>
          </div>
        </div>
      @endif

      @if($currentUser and ($comment->isAuthor() or $currentUser->isAdmin()))
        @include('comments.partial.edit')
      @endif

      @if($currentUser)
        @include('comments.partial.create', ['parentId' => $comment->id])
      @endif

      @forelse ($comment->replies as $reply)
        @include('comments.partial.comment', [
          'comment'   => $reply,
          'isReply'   => true,
          'hasChild'  => count($reply->replies),
          'isTrashed' => $reply->trashed()
        ])
      @empty
      @endforelse
    </div>
  </div>
@endif
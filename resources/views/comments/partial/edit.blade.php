<div class="media media__edit">
  <div class="media-body">
    <form action="{{ route('comments.update', $comment->id) }}" method="POST" role="form" class="form-horizontal">
      {!! csrf_field() !!}
      {!! method_field('PUT') !!}

      <div class="form-group" style="width:100%; margin: auto;">
        <textarea name="content" class="form-control" style="width:100%; padding:1rem;">{{ old('content', $comment->content) }}</textarea>
        {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
      </div>

      <p class="text-right" style="margin:0;">
        <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 1rem;">
          {!! icon('plane') !!} Edit
        </button>
      </p>
    </form>
  </div>
</div>
<div class="form-group">
  <label for="title">{{ trans('forum.title') }}</label>
  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $article->title) }}"/>
  {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  <label for="content">{{ trans('forum.content') }}</label>
  <textarea name="content" class="form-control" rows="10">{{ old('content', $article->content) }}</textarea>
  {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group">
  @include('articles.partial.tagselector')
</div>

<div class="form-group">
  <div class="checkbox">
    <label>
      <input type="checkbox" name="notification" checked="{{ $article->notification ?: 'checked' }}">
      {{ trans('forum.notification') }}
    </label>
  </div>
</div>
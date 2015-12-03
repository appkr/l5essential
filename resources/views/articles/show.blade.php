@extends('layouts.master')

@section('content')
  <div class="page-header">
    <h4>
      {!! icon('forum', null, 'margin-right:1rem') !!}
      <a href="{{ route('articles.index') }}">
        {{ trans('forum.title_forum') }}
      </a>
      <small> / </small>
      {{ $article->title }}
    </h4>
  </div>

  <div class="row container__forum">
    <div class="col-md-3 sidebar__forum">
      <aside>
        @include('layouts.partial.search')
        @include('tags.partial.index')
      </aside>
    </div>

    <div class="col-md-9">
      <article>
        @include('articles.partial.article', ['article' => $article])

        @include('attachments.partial.list', ['attachments' => $article->attachments])

        <p>
          {!! markdown($article->content) !!}
        </p>

        <div class="divider">&nbsp;</div>

        @if ($currentUser and ($currentUser->isAdmin() or $article->isAuthor()))
        <div class="text-center">
          <form action="{{ route('articles.destroy', $article->id) }}" method="post">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
            <button type="submit" class="btn btn-danger">
              {!! icon('delete') !!} Delete
            </button>
            <a href="{{route('articles.edit', $article->id)}}" class="btn btn-info">
              {!! icon('pencil') !!} Edit
            </a>
          </form>
        </div>
        @endif
      </article>

      <hr class="divider"/>

      <article>
        @include('comments.index')
      </article>
    </div>

    @include('layouts.partial.markdown')
  </div>
@stop
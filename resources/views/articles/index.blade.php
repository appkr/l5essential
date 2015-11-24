@extends('layouts.master')

@section('content')
  <div class="page-header">
    <a class="btn btn-primary pull-right" href="{{ route('articles.create') }}">
      {!! icon('forum') !!} {{ trans('forum.create') }}
    </a>
    <h4>
      {!! icon('forum', null, 'margin-right:1rem') !!}
      <a href="{{ route('articles.index') }}">
        {{ trans('forum.title_forum') }}
      </a>
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
        @forelse($articles as $article)
          @include('articles.partial.article', ['article' => $article])
        @empty
          <p class="text-center text-danger">{{ trans('errors.not_found_description') }}</p>
        @endforelse

        <div class="text-center">
          {!! $articles->appends(Request::except('page'))->render() !!}
        </div>
      </article>
    </div>

  </div>

  <!--div class="nav_documents">
    <a type="button" role="button" class="btn btn-sm btn-danger">{{ trans('forum.button_toc') }}</a>
  </div-->
@stop

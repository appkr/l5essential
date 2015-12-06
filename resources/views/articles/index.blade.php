@extends('layouts.master')

@section('content')
  <div class="page-header clearfix">
    <a class="btn btn-primary pull-right" href="{{ route('articles.create') }}">
      {!! icon('new') !!} {{ trans('forum.create') }}
    </a>

    <div class="btn-group pull-right sort__forum hidden-xs">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        {!! icon('sort') !!} {{ trans('common.sort_by') }} <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        @foreach(['created_at' => trans('forum.age'), 'view_count' => trans('forum.view_count')] as $column => $name)
          <li class="{{ Request::input('s') == $column ? 'active' : '' }}">
            {!! link_for_sort($column, $name) !!}
          </li>
        @endforeach
      </ul>
    </div>

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
        @include('articles.partial.search')
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

  <div class="nav__forum">
    <a type="button" role="button" class="btn btn-sm btn-danger">{{ trans('forum.button_toc') }}</a>
  </div>
@stop

@extends('layouts.master')

@section('content')
  <div class="page-header">
    <h4>
      {!! icon('book', null, 'margin-right:1rem') !!}
      <a href="{{ route('lessons.show') }}">
        {{ trans('lessons.title_lessons') }}
      </a>
    </h4>
  </div>

  <div class="row container__lessons">
    <div class="col-md-3 sidebar__lessons">
      <aside>
        {!! markdown($index->content) !!}
      </aside>
    </div>

    <div class="col-md-9 article__lessons">
      <article>
        @include('lessons.partial.pager')

        <hr/>

        {!! markdown($lesson->content) !!}

        <hr/>

        @include('lessons.partial.pager')
      </article>

      <hr class="divider"/>

      <article>
        @include('comments.index', [
          'solved' => false,
          'owner'  => $currentUser && $lesson->isAuthor()
        ])
      </article>
    </div>
  </div>

  <div class="nav__lessons">
    <a type="button" role="button" class="btn btn-sm btn-danger">{{ trans('lessons.button_toc') }}</a>
  </div>

  @include('layouts.partial.markdown')
@stop
@extends('layouts.master')

@section('content')
  <div class="page-header">
    <h4>
      {!! icon('book', null, 'margin-right:1rem') !!}
      <a href="{{ route('documents.show') }}">
        {{ trans('documents.title_documents') }}
      </a>
    </h4>
  </div>

  <div class="row container__documents">
    <div class="col-md-3 sidebar__documents">
      <aside>
        {!! markdown($index->content) !!}
      </aside>
    </div>

    <div class="col-md-9 article__documents">
      <article>
        {!! markdown($document->content) !!}
      </article>

      <hr class="divider"/>

      <article>
        @include('comments.index', [
          'solved' => false,
          'owner'  => $currentUser && $document->isAuthor()
        ])
      </article>
    </div>
  </div>

  <div class="nav__documents">
    <a type="button" role="button" class="btn btn-sm btn-danger">{{ trans('documents.button_toc') }}</a>
  </div>

  @include('layouts.partial.markdown')
@stop
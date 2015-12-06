@extends('layouts.master')

@section('content')
  <div class="page-header">
    <h4>
      {!! icon('forum', null, 'margin-right:1rem') !!}
      <a href="{{ route('articles.index') }}">
        {{ trans('forum.title_forum') }}
      </a>
      <small> / </small>
      <a href="{{ route('articles.edit', $article->id) }}">{{ $article->title }}</a>
      <small> / </small>
      Edit
    </h4>
  </div>

  <div class="container__forum">
    <form action="{{ route('articles.update', $article->id) }}" method="POST" role="form" class="form__forum">
      {!! csrf_field() !!}
      {!! method_field('PUT') !!}

      @include('articles.partial.form')

      <div class="form-group">
        <p class="text-center">
          <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-default">
            {!! icon('reset') !!} {{ trans('common.reset') }}
          </a>
          <button type="submit" class="btn btn-primary">
            {!! icon('plane') !!} {{ trans('common.edit') }}
          </button>
        </p>
      </div>
    </form>
  </div>
@stop

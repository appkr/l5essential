@extends('layouts.master')

@section('content')
  <header class="page-header">
    <h1><i class="fa fa-book icons"></i> Documents Viewer</h1>
  </header>

  <div class="row container__documents">
    <div class="col-md-3 sidebar__documents">
      <aside>
        {!! $index !!}
      </aside>
    </div>

    <div class="col-md-9 article__documents">
      <article>
        {!! $content !!}
      </article>
    </div>
  </div>

  <!--div class="nav_documents">
    <a type="button" role="button" class="btn btn-sm btn-danger">Document Index</a>
  </div-->
@stop
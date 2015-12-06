<form action="{{ route('articles.index') }}" method="get" role="search" id="search__forum">
  <input type="text" name="q" value="{{ Request::input('q') }}" class="form-control" placeholder="{{ trans('common.search') }}"/>
</form>

<p class="lead">
  {!! icon('filter') !!} Filters
</p>

<ul class="list-unstyled">
  @foreach(config('project.filters.article') as $filter => $name)
    <li class="{{ (Request::input(config('project.params.filter')) == $filter) ? 'active' : '' }}">
      <a href="{{ route('articles.index', [config('project.params.filter') => $filter]) }}">
        {{ $name }}
      </a>
    </li>
  @endforeach
</ul>

<p class="lead">
  {!! icon('tags') !!} Tags
</p>

<ul class="list-unstyled">
  @foreach($allTags as $tag)
    <li class="{{ (Route::current()->parameter('slug') == $tag->slug) ? 'active' : '' }}">
      <a href="{{ route('tags.articles.index', $tag->slug) }}">
        {{ $tag->name }}
        @if ($tagCount = $tag->articles->count())
          <span class="badge badge-default">{{ $tagCount }}</span>
        @endif
      </a>
    </li>
  @endforeach
</ul>
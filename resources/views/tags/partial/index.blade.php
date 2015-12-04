<p class="lead">
  {!! icon('filter') !!} Filters
</p>

<ul class="list-unstyled">
  @foreach(['nocomment' => 'No Comment', 'notsolved' => 'Not Solved'] as $filter => $name)
    <li class="{{ (Request::input('f') == $filter) ? 'active' : '' }}">
      <a href="{{ route('articles.index', ['f' => $filter]) }}">
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
    <li class="{{ (Route::current()->parameter('id') == $tag->id) ? 'active' : '' }}">
      <a href="{{ route('tags.articles.index', $tag->id) }}">
        {{ $tag->name }}
        @if ($tagCount = $tag->articles->count())
          <span class="badge badge-default">{{ $tagCount }}</span>
        @endif
      </a>
    </li>
  @endforeach
</ul>
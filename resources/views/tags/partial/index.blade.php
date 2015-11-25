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

{{--Todo : Should we add filter here?--}}
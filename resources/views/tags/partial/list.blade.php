@if ($tags->count())
  <span class="text-muted">{!! icon('tags') !!}</span>

  <ul class="tags__forum">
    @foreach ($tags as $tag)
      <li class="label label-default">
        <a href="{{ route('tags.articles.index', $tag->slug) }}">{{ $tag->name }}</a>
      </li>
    @endforeach
  </ul>
@endif
<p class="lead">
  {!! icon('tags') !!} Tags
</p>

<ul class="list-unstyled">
  {{--Todo : should add link and apply 'active' class--}}
  @foreach($allTags as $tag)
    <li>
      <a href="#">
        {{ $tag->name }}
        @if ($tagCount = $tag->articles->count())
          <span class="badge badge-default">{{ $tagCount }}</span>
        @endif
      </a>
    </li>
  @endforeach
</ul>


{{--Todo : Should we add filter here?--}}
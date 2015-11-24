<span class="text-muted">{!! icon('tags') !!}</span>

<ul class="tags__forum">
  {{--Todo : should add link--}}
  @foreach ($tags as $tag)
    <li class="label label-default">
      <a href="#">{{ $tag->name }}</a> </li>
  @endforeach
</ul>
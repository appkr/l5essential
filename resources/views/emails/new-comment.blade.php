<h1>A new comment created <small>(or updated)</small></h1>

<hr/>

<ul style="list-style: none;">
  <li>{{ $comment->author->name }} <{{ $comment->author->email }}></li>
  <li>{{ $comment->created_at }}</li>
</ul>

<hr/>

<article>
  {!! markdown($comment->content) !!}
</article>
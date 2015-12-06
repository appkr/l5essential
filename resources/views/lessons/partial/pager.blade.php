<nav>
  <ul class="pager">
    <li class="previous {{ $prev === false ? 'disabled' : ''}}">
      <a href="{{ $prev !== false ? route('lessons.show', $prev) : '#'}}">
        {{ trans('pagination.previous') }}
      </a>
    </li>
    <li class="next {{ $next === false ? 'disabled' : ''}}">
      <a href="{{ $next !== false ? route('lessons.show', $next) : '#' }}">
        {{ trans('pagination.next') }}
      </a>
    </li>
  </ul>
</nav>
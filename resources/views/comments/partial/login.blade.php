<div class="media login__forum">
  <div class="media-body">
    <h4 class="media-heading text-center">
      <a href="{{ route('sessions.create', ['return' => urlencode($currentUrl)]) }}">
        {{ trans('auth.title_login') }}</a>
      {{ trans('forum.msg_ask_login') }}
    </h4>
  </div>
</div>
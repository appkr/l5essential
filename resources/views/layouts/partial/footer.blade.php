<footer class="footer">
  <ul class="list-inline pull-right locale">
    <li>{!! icon('locale') !!}</li>
    @foreach (['en' => 'English', 'ko' => '한국어'] as $locale => $language)
      <li class="{{ ($locale == $currentLocale) ? 'active' : '' }}">
        <a href="{{ route('locale', ['locale' => $locale, 'return' => urlencode($currentUrl)]) }}">
          {{ $language }}
        </a>
      </li>
    @endforeach
  </ul>

  <div>
    &copy; {{ date('Y') }} &nbsp; <a href="https://github.com/appkr/l5essential">Laravel 5 Essential</a>
  </div>
</footer>

<div>
  <a id="back-to-top" href="#" class="btn btn-sm btn-danger back-to-top" role="button" title="Top">
    <i class="fa fa-chevron-up"></i>
  </a>
</div>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">

  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a href="{{ route('home') }}" class="navbar-brand">
        <img src="/images/laravel_logo.png" style="display: inline-block; height: 30px;"/>
      </a>
    </div>

    <div class="collapse navbar-collapse navbar-responsive-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="{{ route('documents.show') }}">
            {!! icon('book') !!} {{ trans('documents.title_documents') }}
          </a>
        </li>
        <li>
          <a href="{{ route('articles.index') }}">
            {!! icon('forum') !!} {{ trans('forum.title_forum') }}
          </a>
        </li>

        @if(! auth()->check())
          <li>
            <a href="{{ route('session.create') }}">
              {!! icon('login') !!} {{ trans('auth.title_login') }}
            </a>
          </li>
          <li>
            <a href="{{ route('user.create') }}">
              {!! icon('certificate') !!} {{ trans('auth.title_signup') }}
            </a>
          </li>
        @else
          <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              {!! icon('user') !!} {{ $currentUser->name }} <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
              <li>
                <a href="{{ route('session.destroy') }}">
                  {!! icon('logout') !!} {{ trans('auth.title_logout') }}
                </a>
              </li>
            </ul>
          </li>
        @endif
      </ul>
    </div>
  </div>
</nav>
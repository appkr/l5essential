<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

  <meta name="description" content="">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="google-site-verification" content="VwIUcuPRo2jMuV269tve2tZo3jI-JCnrdkR57RzzxrM" />
  <meta name="naver-site-verification" content="7cebcc8e5493169f5401870d9ce57f48d18491cd"/>
  <meta property="og:site_name" content="Laravel 5 Essential" />
  <meta property="og:image" content="//ec2-52-193-67-224.ap-northeast-1.compute.amazonaws.com/images/laravel_logo.png" />
  <meta property="og:type" content="Website" />
  <meta property="article:author" content="appkr (juwonkim@me.com)" />

  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="route" content="{{ $currentRouteName }}" />

  <title>Laravel 5 Essential</title>

  <link href="{{ elixir("css/app.css") }}" rel="stylesheet">
  @yield('style')

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body>
  @include('layouts.partial.navigation')

  @include('layouts.partial.flash_message')

  <div class="container">
    @yield('content')
  </div>

  @include('layouts.partial.footer')

  <script src="{{ elixir("js/app.js") }}"></script>
  @yield('script')
  @include('layouts.partial.tracker')
</body>

</html>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>Laravel 5 Essential</title>

    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    @yield('style')
</head>
<body>
    <div class="container">
        @yield('content')

        @include('footer')
    </div>

    <script src=" {{ elixir('js/app.js') }}"></script>
    @yield('script')

</body>
</html>
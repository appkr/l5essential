<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel 5 Essential</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    @yield('style')
</head>
<body>
    <div class="container">
        @yield('content')

        @include('footer')
    </div>

        @yield('script')

</body>
</html>
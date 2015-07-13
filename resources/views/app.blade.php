<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vouliwatch</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">
</head>
<body>
    <div class="container">
        <!-- NAV BAR HERE -->

        <!-- Flash message -->
        @include('partials.flash')

        @yield('content')
    </div>

    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>

    @yield('footer')
</body>
</html>
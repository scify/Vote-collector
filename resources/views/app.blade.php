<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vouliwatch</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">
</head>
<body>
    <!-- nav bar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="bs-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <a class="navbar-brand" href="/">Vouliwatch</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="{{ Request::path() == 'groups' ? 'active' : '' }}">
                        <a href="/groups">Κοιν. Ομάδες</a>
                    </li>
                    <li class="{{ Request::path() == 'members' ? 'active' : '' }}">
                        <a href="/members">Βουλευτές</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Διαχείρηση <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ Request::path() == 'votings' ? 'active' : '' }}">
                                <a href="/votings">Ψηφοφορίες</a>
                            </li>
                            <li class="{{ Request::path() == 'votetypes' ? 'active' : '' }}">
                                <a href="/votetypes">Τύποι ψηφοφορίας</a>
                            </li>
                            <li class="{{ Request::path() == 'voteobjectives' ? 'active' : '' }}">
                                <a href="/voteobjectives">Αντικείμενα ψηφοφορίας</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @include('partials.flash')

        @yield('content')
    </div>

    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>

    @yield('footer')
</body>
</html>
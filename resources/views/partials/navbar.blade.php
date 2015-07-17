<nav role="navigation" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Vouliwatch</a>
        </div>

        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @include('partials.navbarlink', ['link' => 'groups', 'linkText' => 'Κοιν. Ομάδες'])
                @include('partials.navbarlink', ['link' => 'members', 'linkText' => 'Βουλευτές'])
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Διαχείριση <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @include('partials.navbarlink', ['link' => 'votings', 'linkText' => 'Ψηφοφορίες'])
                        @include('partials.navbarlink', ['link' => 'votetypes', 'linkText' => 'Τύποι ψηφοφορίας'])
                        @include('partials.navbarlink', ['link' => 'voteobjectives', 'linkText' => 'Αντικείμενα ψηφοφορίας'])
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

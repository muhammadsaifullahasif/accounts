<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-secondary navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" id="nav-logout-form">
                @csrf
            </form>
            <a class="nav-link" onclick="event.preventDefault();document.getElementById('nav-logout-form').submit();" title="Logout" href="{{ route('logout') }}" role="button">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
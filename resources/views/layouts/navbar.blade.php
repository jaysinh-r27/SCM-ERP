<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
            </form>
        </li>
    </ul>
</nav>

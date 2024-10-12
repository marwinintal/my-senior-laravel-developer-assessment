<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">SSA Academy</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
                    </li>
                </ul>
                <form method="POST" action="{{ route('logout') }}" class="form-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger my-2 my-sm-0">Logout</button>
                </form>
            </div>
        </div>
    </nav>
</header>

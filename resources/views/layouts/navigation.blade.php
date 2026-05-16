<nav class="navbar">

    <div class="nav-container">

        <div class="logo">
            <a href="{{ route('dashboard') }}">
                VaultScribe
            </a>
        </div>

        <div class="nav-links">

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

            <a href="{{ route('notes.trash') }}">
                Trash
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="logout-btn">
                    Logout
                </button>
            </form>

        </div>

    </div>

</nav>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SCENEPIX - Movie Reviews & TV Shows</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- CSS utama --}}
    <link rel="stylesheet" href="{{ asset('css/scenepix.css') }}">

    {{-- Untuk halaman khusus kalau butuh CSS tambahan --}}
    @yield('styles')
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar-custom">
    <div class="nav-left">
        <i class="fas fa-film nav-logo-icon"></i>
        <span class="nav-logo-text">SCENEPIX</span>
    </div>

    <ul class="nav-menu">
    <li class="{{ request()->is('/') ? 'active' : '' }}">
        <a href="{{ url('/') }}">Home</a>
    </li>

    <li class="{{ request()->is('review') ? 'active' : '' }}">
        <a href="{{ url('/review') }}">Review</a>
    </li>

    <li class="{{ request()->is('films') ? 'active' : '' }}">
        <a href="{{ url('/films') }}">Films</a>
    </li>

    <li class="{{ request()->is('tv-shows') ? 'active' : '' }}">
        <a href="{{ url('/tv-shows') }}">TV Show</a>
    </li>

    <li class="{{ request()->is('signup') ? 'active' : '' }}">
        <a href="{{ url('/signup') }}">Sign Up</a>
    </li>
</ul>


    <form action="{{ route('titles.search') }}" method="GET" class="search-wrapper">
        <input type="text" name="q" placeholder="Search movies, TV shows..." class="search-input">
        <button type="submit" class="search-btn">
            <i class="fas fa-search"></i>
        </button>
    </form>
</nav>


    <!-- CONTENT -->
    <main class="scenepix-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="scenepix-footer">
        <div class="container text-center">
            <div class="footer-logo">SCENEPIX</div>
            <p class="footer-text">
                © 2024 SCENEPIX. Powered by Laravel
            </p>
        </div>
    </footer>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>

</body>
</html>

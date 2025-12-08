<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showfy - Temukan Film & Serial TV Favorit</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- Google Fonts (Poppins) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS Kustom --}}
    <link rel="stylesheet" href="{{ asset('css/showfy.css') }}">
    @yield('styles')
</head>
<body>

    {{-- NAVBAR BARU --}}
    <header class="navbar-custom">
        <div class="container-fluid">
            <div class="navbar-top">
                <a href="{{ url('/') }}" class="navbar-brand">
                    <i class="fas fa-play-circle brand-icon"></i>
                    <span class="brand-text">Showfy</span>
                </a>
                <button class="btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fas fa-user"></i> Login
                </button>
            </div>
            <nav class="navbar-menu">
                <ul class="nav-list">
                    <li class="{{ request()->is('/') ? 'active' : '' }}">
                        <a href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="{{ request()->is('films') ? 'active' : '' }}">
                        <a href="{{ route('films.index') }}">Films</a>
                    </li>
                    <li class="{{ request()->is('tv-shows') ? 'active' : '' }}">
                        <a href="{{ route('tv.index') }}">TV Shows</a>
                    </li>
                </ul>
            </nav>
            <div class="navbar-search">
                <form action="{{ route('titles.search') }}" method="GET">
                    <input type="text" name="q" class="search-input" placeholder="Cari film, serial, aktor..." value="{{ request('q') }}">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </header>

    {{-- MODAL LOGIN --}}
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100 text-center" id="loginModalLabel">Login ke Showfy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="Masukkan username">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Masukkan password">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer-custom">
        <div class="container text-center">
            <span class="footer-brand">Showfy</span>
            <p>&copy; 2024 Showfy. Made with <i class="fas fa-heart text-pink"></i> using Laravel.</p>
        </div>
    </footer>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
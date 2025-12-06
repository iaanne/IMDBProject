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
    <nav class="navbar navbar-expand-lg navbar-dark scenepix-navbar">
        <div class="container">

            <a class="navbar-brand scenepix-brand" href="{{ url('/') }}">
                <i class="fas fa-film me-2"></i>SCENEPIX
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            Home
                        </a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#">Review</a></li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('films') ? 'active' : '' }}"
                           href="{{ route('films.index') }}">
                           Films
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('tv-shows') ? 'active' : '' }}"
                           href="{{ route('tv.index') }}">
                           TV Show
                        </a>
                    </li>
                </ul>

                <!-- SEARCH -->
                <form action="{{ route('titles.search') }}" method="GET" class="d-flex scenepix-search-box">
                    <input type="text" name="q" class="search-input"
                           placeholder="Search here..." value="{{ request('q') }}">
                    
                    <button class="search-btn" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

            </div>

        </div>
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

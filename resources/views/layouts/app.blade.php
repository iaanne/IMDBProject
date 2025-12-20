<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Showfy - Temukan Film & Serial TV Favorit')</title>

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
    <header class="navbar-custom">
        {{-- BARIS ATAS: LOGO, SEARCH, LOGIN, HAMBURGER --}}
        <div class="navbar-top">
            <a href="{{ url('/') }}" class="navbar-brand">
                <i class="fas fa-play-circle brand-icon"></i>
                <span class="brand-text">Showfy</span>
            </a>

            <div class="navbar-right">
                <form action="{{ route('search') }}" method="GET" class="navbar-search">
                    <input type="text" name="q" class="search-input" placeholder="Cari film, serial..."
                        value="{{ request('q') }}">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
                
                @auth
                    {{-- Kalau sudah login - tampilkan dropdown user --}}
                    <div class="dropdown d-inline-block">
                        <button class="btn-login dropdown-toggle" type="button" id="userDropdown" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> 
                            <span class="d-none d-md-inline">{{ Auth::user()->username }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><span class="dropdown-item-text"><strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            
                            @if(Auth::user()->role === 'executive')
                                <li><a class="dropdown-item" href="{{ route('executive.dashboard') }}">
                                    <i class="fas fa-chart-line"></i> Dashboard
                                </a></li>
                            @endif
                            
                            @if(Auth::user()->role === 'production')
                                <li><a class="dropdown-item" href="{{ route('production.dashboard') }}">
                                    <i class="fas fa-tools"></i> Dashboard
                                </a></li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    {{-- Kalau belum login - tampilkan button login --}}
                    <button class="btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-user"></i>
                    </button>
                @endauth
                
                <button class="hamburger-btn" id="hamburgerBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        {{-- BARIS MENU: HOME, FILMS, TV SHOWS --}}
        <nav class="navbar-menu" id="navbarMenu">
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
                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Success Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username"
                                   placeholder="Masukkan username"
                                   value="{{ old('username') }}"
                                   required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   placeholder="Masukkan password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="rememberMe" 
                                   name="remember">
                            <label class="form-check-label" for="rememberMe">Ingat saya</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Demo: <strong>native_user / native123</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- CONTENT --}}
<main class="main-content">
    {{-- Flash Messages (Success/Error) --}}
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

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
    <script src="{{ asset('js/home.js') }}"></script>

    {{-- SCRIPT UNTUK HAMBURGER MENU --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const navbar = document.querySelector('.navbar-custom');

            if (hamburgerBtn) {
                hamburgerBtn.addEventListener('click', function() {
                    navbar.classList.toggle('is-active');
                });
            }
        });
    </script>

    {{-- Auto show modal kalau ada error login --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            });
        </script>
    @endif

    @yield('scripts')
</body>



</html>


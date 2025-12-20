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
    
    {{-- Additional CSS for Production/Executive Menus --}}
    <style>
        /* Dropdown Menu di Navbar */
        .navbar-menu .nav-list li.dropdown {
            position: relative;
        }
        
        .navbar-menu .dropdown-toggle {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0.75rem 1rem;
        }
        
        /* PENTING: Tambah padding-bottom untuk gap antara menu dan dropdown */
        .navbar-menu li.dropdown:hover::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 15px;
            background: transparent;
        }
        
        .navbar-menu .dropdown-menu {
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            z-index: 1000;
            display: none;
            min-width: 220px;
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            padding: 8px 0;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        
        .navbar-menu li.dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        .navbar-menu .dropdown-menu li {
            list-style: none;
            padding: 0;
        }
        
        .navbar-menu .dropdown-menu .dropdown-item {
            display: block;
            padding: 10px 20px;
            color: #e2e8f0;
            text-decoration: none;
            transition: background-color 0.3s;
            white-space: nowrap;
        }
        
        .navbar-menu .dropdown-menu .dropdown-item:hover {
            background-color: #334155;
            color: #22d3ee;
        }
        
        .navbar-menu .dropdown-menu .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
        }
        
        .navbar-menu .dropdown-menu .dropdown-divider {
            height: 1px;
            background-color: #334155;
            margin: 5px 0;
            border: none;
        }
        
        /* User Dropdown (kanan atas) */
        .dropdown-menu-dark {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
        }
        
        .dropdown-menu-dark .dropdown-item {
            color: #e2e8f0;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }
        
        .dropdown-menu-dark .dropdown-item:hover {
            background-color: #334155;
            color: #22d3ee;
        }
        
        .dropdown-menu-dark .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
        }
        
        .dropdown-menu-dark .dropdown-divider {
            border-color: #334155;
            margin: 5px 0;
        }
        
        .dropdown-menu-dark .dropdown-item-text {
            padding: 10px 20px;
            color: #94a3b8;
        }
        
        /* Dropdown button styling */
        .btn-login.dropdown-toggle::after {
            margin-left: 8px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-menu .dropdown-menu {
                position: static;
                margin-top: 5px;
                box-shadow: none;
                border-left: 3px solid #06b6d4;
                transform: none !important;
            }
            
            .navbar-menu .dropdown-menu .dropdown-item {
                padding-left: 30px;
            }
            
            .navbar-menu li.dropdown:hover::after {
                display: none;
            }
        }
        
        /* Make dropdown stay open when hovering over dropdown menu itself */
        .navbar-menu .dropdown-menu:hover {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    
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
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="userDropdown">
                            <li><span class="dropdown-item-text"><strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            
                            @if(Auth::user()->role === 'executive')
                                <li><a class="dropdown-item" href="{{ route('executive.dashboard') }}">
                                    <i class="fas fa-chart-line"></i> Dashboard
                                </a></li>
                            @endif
                            
                            @if(Auth::user()->role === 'production')
                                <li><a class="dropdown-item" href="{{ route('production.dashboard') }}">
                                    <i class="fas fa-video"></i> Dashboard
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

        {{-- BARIS MENU: HOME, FILMS, TV SHOWS, PRODUCTION (conditional), EXECUTIVE (conditional) --}}
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
                
                {{-- Menu Production (hanya muncul untuk production user) --}}
                @if(Auth::check() && Auth::user()->role === 'production')
                <li class="dropdown {{ request()->is('production*') ? 'active' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-video"></i> Production <i class="fas fa-chevron-down ms-1"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('production.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('production.movies.index') }}">
                                <i class="fas fa-film"></i> Manage Movies
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('production.shows.index') }}">
                                <i class="fas fa-play-circle"></i> Manage Shows
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('production.episodes.index') }}">
                                <i class="fas fa-list-ol"></i> Manage Episodes
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                
                {{-- Menu Executive (hanya muncul untuk executive user) --}}
                @if(Auth::check() && Auth::user()->role === 'executive')
                <li class="{{ request()->is('executive*') ? 'active' : '' }}">
                    <a href="{{ route('executive.dashboard') }}">
                        <i class="fas fa-chart-line"></i> Analytics
                    </a>
                </li>
                @endif
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
                            Demo: <strong>production_user / production123</strong>
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
            <p>&copy; 2024 Showfy. Made with <i class="fas fa-heart text-danger"></i> using Laravel.</p>
        </div>
    </footer>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>

    {{-- SCRIPT UNTUK HAMBURGER MENU & DROPDOWN --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hamburger menu toggle
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const navbar = document.querySelector('.navbar-custom');

            if (hamburgerBtn) {
                hamburgerBtn.addEventListener('click', function() {
                    navbar.classList.toggle('is-active');
                });
            }
            
            // Production dropdown untuk mobile (click to toggle)
            const isMobile = window.innerWidth <= 768;
            if (isMobile) {
                const dropdownToggles = document.querySelectorAll('.navbar-menu .dropdown-toggle');
                
                dropdownToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        const parent = this.closest('.dropdown');
                        const menu = parent.querySelector('.dropdown-menu');
                        
                        // Toggle display
                        if (menu.style.display === 'block') {
                            menu.style.display = 'none';
                        } else {
                            // Close other dropdowns
                            document.querySelectorAll('.navbar-menu .dropdown-menu').forEach(m => {
                                m.style.display = 'none';
                            });
                            menu.style.display = 'block';
                        }
                    });
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
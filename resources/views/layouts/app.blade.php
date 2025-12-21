<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Showfy - Streaming Premium')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <style>
        /* === SHOWFY PREMIUM THEME ENGINE === */
        :root {
            --c-onyx: #0d0d0d;
            --c-rose: #d95f8c;
            --c-amaranth: #870339;
            --bg-main: var(--c-onyx);
            --bg-card: #141414;
            --bg-glass: rgba(13, 13, 13, 0.85);
            --text-main: #ffffff;
            --text-muted: #a3a3a3;
            --border-color: rgba(255, 255, 255, 0.15);
            --gradient-primary: linear-gradient(135deg, var(--c-amaranth) 0%, var(--c-rose) 100%);
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-main); }
        ::-webkit-scrollbar-thumb { background: var(--c-amaranth); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--c-rose); }

        /* Navbar */
        .navbar-custom {
            background-color: var(--bg-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand i { color: var(--c-rose); }

        .nav-link {
            color: var(--text-muted) !important;
            font-weight: 500;
            margin: 0 12px;
            transition: 0.3s;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: white !important;
            text-shadow: 0 0 10px rgba(255,255,255,0.3);
        }

        /* Search Input */
        .search-input-nav {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            padding-right: 40px;
            width: 250px;
            transition: all 0.3s;
        }

        .search-input-nav:focus {
            background: #000;
            border-color: var(--c-rose);
            box-shadow: 0 0 15px rgba(217, 95, 140, 0.2);
            outline: none;
            color: white;
        }

        .search-btn-nav {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
        }
        .search-btn-nav:hover { color: white; }

        /* Buttons */
        .btn-gradient {
            background: var(--gradient-primary);
            color: white;
            padding: 8px 25px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(135, 3, 57, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(217, 95, 140, 0.5);
            color: white;
        }
        
        .btn-outline-custom {
            background: transparent;
            border: 1px solid var(--c-rose);
            color: var(--c-rose);
            padding: 8px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-outline-custom:hover {
            background: var(--c-rose);
            color: white;
        }

        /* Dropdown & Modal */
        .dropdown-menu-dark-custom {
            background-color: #1a1a1a;
            border: 1px solid var(--border-color);
            margin-top: 15px;
            border-radius: 12px;
        }
        .dropdown-item { color: var(--text-muted); padding: 10px 20px; }
        .dropdown-item:hover { background-color: rgba(255,255,255,0.05); color: var(--c-rose); }
        .dropdown-divider { border-color: var(--border-color); }

        .modal-content {
            background-color: #121212;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 16px;
        }
        .modal-header { border-bottom: 1px solid rgba(255,255,255,0.05); }
        
        .modal-body .form-control {
            background: #080808;
            border: 1px solid #333;
            color: white;
            border-radius: 50px;
            padding: 10px 20px;
        }
        .modal-body .form-control:focus {
            background: #000;
            border-color: var(--c-rose);
            box-shadow: 0 0 0 0.2rem rgba(217, 95, 140, 0.2);
            color: white;
        }
        .modal-body .form-label { color: #e0e0e0; font-size: 0.85rem; font-weight: 600; margin-left: 10px; }
        .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }

        /* Footer */
        .footer-custom {
            background: #050505;
            padding: 60px 0;
            margin-top: 80px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-muted);
        }

        /* Alert Styling */
        .alert { border-radius: 12px; }
        .alert-success { background: rgba(25, 135, 84, 0.2); border-color: #198754; color: #75b798; }
        .alert-danger { background: rgba(220, 53, 69, 0.2); border-color: #dc3545; color: #ea868f; }

        @media (max-width: 991px) {
            .search-input-nav { width: 100%; margin-bottom: 15px; }
            .navbar-nav { margin-top: 15px; margin-bottom: 15px; }
        }
    </style>
    
    @yield('styles')
</head>

<body>
    
    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-play-circle"></i> Showfy
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" style="border: none; color: white;">
                <i class="fas fa-bars fa-lg"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                {{-- Menu Tengah --}}
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('films*') ? 'active' : '' }}" href="{{ route('films.index') }}">Films</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('tv-shows*') ? 'active' : '' }}" href="{{ route('tv.index') }}">TV Shows</a>
                    </li>

                    @if(Auth::check() && Auth::user()->role === 'production')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-warning" href="#" data-bs-toggle="dropdown">Production</a>
                            <ul class="dropdown-menu dropdown-menu-dark-custom">
                                <li><a class="dropdown-item" href="{{ route('production.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('production.movies.index') }}">Manage Movies</a></li>
                                <li><a class="dropdown-item" href="{{ route('production.shows.index') }}">Manage Shows</a></li>
                                <li><a class="dropdown-item" href="{{ route('production.episodes.index') }}">Manage Episodes</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(Auth::check() && Auth::user()->role === 'executive')
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('executive.dashboard') }}">Analytics</a>
                        </li>
                    @endif
                </ul>

                {{-- Kanan: Search & User --}}
                <div class="d-flex align-items-center gap-3">
                    <form action="{{ route('search') }}" method="GET" class="position-relative d-none d-lg-block">
                        <input type="text" name="q" class="search-input-nav" placeholder="Cari..." value="{{ request('q') }}">
                        <button type="submit" class="search-btn-nav"><i class="fas fa-search"></i></button>
                    </form>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-gradient dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->username }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark-custom">
    <li><span class="dropdown-item disabled text-white">Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong></span></li>
    <li><hr class="dropdown-divider"></li>
    
    {{-- TAMBAHKAN BAGIAN INI --}}
    <li>
        <a class="dropdown-item" href="{{ route('watchlist.index') }}">
            <i class="fas fa-bookmark me-2 text-pink"></i> My Watchlist
        </a>
    </li>
    {{-- END TAMBAHAN --}}

    @if(Auth::user()->role === 'executive' || Auth::user()->role === 'production')
        
    @endif
    
    <li>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </li>
</ul>
                        </div>
                    @else
                        {{-- Tombol Login & Register --}}
                        <div class="d-flex gap-2">
                            <button class="btn btn-gradient px-4" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Login
                            </button>
                            <button class="btn btn-outline-custom px-4" data-bs-toggle="modal" data-bs-target="#registerModal">
                                Daftar
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    {{-- MODAL LOGIN --}}
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5 pt-0 text-center">
                    <i class="fas fa-sign-in-alt fa-3x mb-3" style="color: var(--c-rose);"></i>
                    <h3 class="fw-bold mb-4 text-white">Login Akun</h3>
                    
                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3 text-start">
                            <label class="form-label">USERNAME</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-4 text-start">
                            <label class="form-label">PASSWORD</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-gradient w-100 py-2 rounded-pill shadow-lg">Masuk Sekarang</button>
                    </form>
                    
                    <div class="mt-4 text-muted small">
                        Belum punya akun? <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal" class="text-white fw-bold">Daftar disini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL REGISTER (BARU) --}}
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5 pt-0 text-center">
                    <i class="fas fa-user-plus fa-3x mb-3" style="color: var(--c-rose);"></i>
                    <h3 class="fw-bold mb-2 text-white">Buat Akun Baru</h3>
                    <p class="text-muted small mb-4">Bergabunglah dengan Showfy sekarang!</p>
                    
                    <form action="{{ route('register.submit') }}" method="POST">
                        @csrf
                        {{-- Username --}}
                        <div class="mb-3 text-start">
                            <label class="form-label">USERNAME</label>
                            <input type="text" name="username" class="form-control" placeholder="Pilih username unik" required>
                        </div>
                        
                        {{-- Password --}}
                        <div class="row">
                            <div class="col-md-6 mb-3 text-start">
                                <label class="form-label">PASSWORD</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3 text-start">
                                <label class="form-label">ULANGI PASS</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        {{-- Token Section --}}
                        <div class="mb-4 text-start">
                            <label class="form-label text-warning"><i class="fas fa-key me-1"></i> TOKEN AKSES (OPSIONAL)</label>
                            <input type="text" name="token" class="form-control border-warning" placeholder="Isi token jika ada">
                            <div class="form-text text-muted fst-italic ms-2">
                                *Kosongkan jika Anda adalah penonton (Native User).
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gradient w-100 py-2 rounded-pill shadow-lg">Daftar Sekarang</button>
                    </form>

                    <div class="mt-4 text-muted small">
                        Sudah punya akun? <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal" class="text-white fw-bold">Login disini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main class="min-vh-100">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        {{-- Error Validation --}}
        @if ($errors->any())
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer-custom">
        <div class="container text-center">
            <h3 class="fw-bold text-white mb-2" style="letter-spacing: -1px;">Showfy.</h3>
            <p class="small opacity-50 mb-4">Ultimate Streaming Experience</p>
            <div class="d-flex justify-content-center gap-4 mb-4">
                <a href="#" class="text-white opacity-50 hover-opacity-100"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-white opacity-50 hover-opacity-100"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="#" class="text-white opacity-50 hover-opacity-100"><i class="fab fa-youtube fa-lg"></i></a>
            </div>
            <p class="small mb-0 opacity-25">&copy; 2025 Showfy Inc. All rights reserved.</p>
        </div>
    </footer>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Auto Show Modal if Error --}}
    @if ($errors->has('username') || $errors->has('password'))
        {{-- Kalau error login/register umum, tampilkan login --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Cek apakah error berasal dari register (ada field token/konfirmasi)
                @if($errors->has('token') || $errors->has('password_confirmation'))
                    new bootstrap.Modal(document.getElementById('registerModal')).show();
                @else
                    new bootstrap.Modal(document.getElementById('loginModal')).show();
                @endif
            });
        </script>
    @endif

    @yield('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Showfy - Welcome</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* === KONFIGURASI TEMA SHOWFY === */
        :root {
            --bg-main: #0d0d0d;
            --bg-card: rgba(20, 20, 20, 0.8);
            --primary-pink: #d95f8c;
            --primary-red: #870339;
            --text-muted: #a3a3a3;
            --border-color: rgba(255, 255, 255, 0.1);
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-main);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            
            /* Background Gradient Halus */
            background: radial-gradient(circle at 10% 20%, rgba(135, 3, 57, 0.2) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(217, 95, 140, 0.15) 0%, transparent 40%),
                        #0d0d0d;
        }

        /* === NAVBAR SIMPLE === */
        .navbar {
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(90deg, #fff, var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            letter-spacing: -1px;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            margin-left: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary-pink);
        }

        .nav-btn {
            background: var(--primary-pink);
            color: white !important;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(217, 95, 140, 0.3);
        }

        .nav-btn:hover {
            background: var(--primary-red);
            transform: translateY(-2px);
        }

        /* === MAIN CONTENT === */
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .content-wrapper {
            max-width: 1000px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
        }

        /* Bagian Kiri (Teks) */
        .text-section {
            flex: 1;
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        h1 span {
            color: var(--primary-pink);
        }

        p.lead {
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 450px;
        }

        /* Bagian Kanan (Card Login/Register) */
        .card-section {
            flex: 0 0 400px;
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            text-align: center;
            transition: transform 0.3s;
        }

        .auth-card:hover {
            border-color: var(--primary-pink);
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 3rem;
            color: var(--primary-pink);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .btn-full {
            display: block;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 15px;
            transition: 0.3s;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--primary-red), var(--primary-pink));
            color: white;
            border: none;
        }

        .btn-primary:hover {
            box-shadow: 0 0 20px rgba(217, 95, 140, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border-color);
            color: white;
        }

        .btn-outline:hover {
            border-color: var(--primary-pink);
            color: var(--primary-pink);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .content-wrapper {
                flex-direction: column;
                text-align: center;
            }
            
            p.lead {
                margin-left: auto;
                margin-right: auto;
            }

            .card-section {
                width: 100%;
                max-width: 400px;
            }
            
            h1 { font-size: 2.5rem; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <a href="#" class="brand">Showfy.</a>
        
        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-btn">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-btn">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    {{-- CONTENT --}}
    <div class="main-container">
        <div class="content-wrapper">
            
            {{-- BAGIAN KIRI: TEKS HERO --}}
            <div class="text-section">
                <h1>
                    Dunia Hiburan <br>
                    Tanpa <span>Batas.</span>
                </h1>
                <p class="lead">
                    Jelajahi ribuan film dan serial TV dari seluruh dunia. 
                    Temukan rekomendasi terbaik yang dikurasi khusus untuk Anda.
                </p>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; color: var(--text-muted); font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="color: var(--primary-pink); margin-right: 8px;"></i> Gratis Akses
                    </div>
                    <div style="display: flex; align-items: center; color: var(--text-muted); font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="color: var(--primary-pink); margin-right: 8px;"></i> Update Harian
                    </div>
                    <div style="display: flex; align-items: center; color: var(--text-muted); font-size: 0.9rem;">
                        <i class="fas fa-check-circle" style="color: var(--primary-pink); margin-right: 8px;"></i> HD Quality
                    </div>
                </div>
            </div>

            {{-- BAGIAN KANAN: KARTU LOGIN --}}
            <div class="card-section">
                <div class="auth-card">
                    <div class="card-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <h2 class="card-title">Mulai Menonton</h2>
                    <p class="card-desc">Masuk untuk menyimpan favorit dan mendapatkan rekomendasi personal.</p>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-full btn-primary">
                                <i class="fas fa-tachometer-alt me-2"></i> Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-full btn-primary">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-full btn-outline">
                                    Buat Akun Baru
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>

        </div>
    </div>

</body>
</html>
@extends('layouts.app')

@section('title', 'Showfy - Temukan Film & Serial TV Favorit')

@section('content')
<style>
    /* === KONFIGURASI WARNA (SAMA DENGAN PAGE LAIN) === */
    :root {
        --bg-main: #0d0d0d;        /* Hitam Pekat */
        --bg-card: #000000;        /* Hitam Card */
        --primary-pink: #d95f8c;   /* Pink Highlight */
        --primary-red: #870339;    /* Burgundy */
        --text-muted: #a3a3a3;
        --border-color: rgba(255, 255, 255, 0.15);
    }

    body {
        background-color: var(--bg-main);
        color: #ffffff;
        font-family: 'Poppins', sans-serif;
    }

    /* Utilities */
    .text-pink { color: var(--primary-pink) !important; }
    
    /* === HERO SECTION (PURE GRADIENT - REVERSED) === */
    .hero-wrapper {
        position: relative;
        height: 85vh;
        min-height: 550px;
        
        /* --- PERBAIKAN GRADASI DISINI --- */
        /* Kita tukar posisi warnanya agar Hitam di awal, Merah di akhir */
        background: 
            /* Layer 1: Cahaya Pink lembut (tetap di kiri atas sebagai highlight) */
            radial-gradient(circle at 20% 30%, rgba(217, 95, 140, 0.2) 0%, transparent 50%),
            
            /* Layer 2: Gradasi Diagonal UTAMA (DIBALIK) */
            /* Mulai dari Hitam Pekat (0%) -> ke Burgundy Cerah (100%) */
            linear-gradient(135deg, var(--bg-main) 0%, #1a0510 50%, var(--primary-red) 100%);
        
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 750px;
        padding-left: 15px;
    }

    /* Kita tidak butuh .hero-overlay lagi karena backgroundnya sudah gelap */

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 750px;
        padding-left: 15px;
    }

    /* Badge Khusus (Ganti dari Merah ke Gradasi Pink) */
    .badge-featured {
        background: linear-gradient(90deg, var(--primary-red), var(--primary-pink));
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 1px;
        box-shadow: 0 5px 15px rgba(217, 95, 140, 0.3); /* Glow Pink */
        border: 1px solid rgba(255,255,255,0.1);
        display: inline-flex;
        align-items: center;
    }

    /* Judul Besar */
    .hero-title {
        font-size: 4.5rem;
        font-weight: 800;
        line-height: 1.1;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        /* Efek Teks Gradasi Putih ke Pink */
        background: linear-gradient(90deg, #ffffff 60%, var(--primary-pink) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 5px 15px rgba(0,0,0,0.5));
    }

    .hero-meta {
        font-size: 1.15rem;
        color: #e5e5e5;
        margin-bottom: 2.5rem;
        display: flex;
        gap: 25px;
        align-items: center;
    }

    /* Tombol Hero */
    .btn-hero {
        background: var(--primary-pink);
        color: white;
        border: none;
        padding: 18px 45px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 10px 30px rgba(217, 95, 140, 0.4);
    }

    .btn-hero:hover {
        background: var(--primary-red);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(135, 3, 57, 0.5);
        color: white;
    }

    /* === SECTION HEADERS === */
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        margin-top: 70px;
        border-left: 5px solid var(--primary-pink);
        padding-left: 20px;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        color: white;
        letter-spacing: -0.5px;
    }

    /* === CARD GRID SYSTEM === */
    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 24px;
        margin-bottom: 60px;
    }

    .movie-card {
        background: var(--bg-card);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Efek membal halus */
        border: 1px solid var(--border-color);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        text-decoration: none;
        color: white;
    }

    .movie-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: var(--primary-pink);
        box-shadow: 0 20px 40px -10px rgba(217, 95, 140, 0.3); /* Pink Glow lebih kuat */
    }

    .movie-card-image {
        height: 280px;
        background: linear-gradient(135deg, #1a1a1a, #050505);
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }

    .movie-card-image i {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.08);
        transition: 0.4s;
    }

    .movie-card:hover .movie-card-image i {
        color: var(--primary-pink);
        transform: scale(1.2) rotate(-10deg);
        filter: drop-shadow(0 0 10px var(--primary-pink));
    }

    .movie-card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .movie-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
        color: white;
    }

    .movie-meta {
        font-size: 0.9rem;
        color: var(--text-muted);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
</style>

{{-- 1. HERO PREVIEW SECTION (PURE GRADIENT) --}}
@if ($featuredMovie)
    {{-- Hapus style background-image --}}
    <div class="hero-wrapper">
        
        {{-- Hapus div hero-overlay --}}

        <div class="container">
            <div class="hero-content">
                {{-- PERBAIKAN BADGE WARNA --}}
                <span class="badge badge-featured">
                    <i class="fas fa-crown me-2"></i> Featured Movie
                </span>
                
                <h1 class="hero-title">{{ $featuredMovie->primaryTitle }}</h1>
                
                <div class="hero-meta">
                    <span>
                        <i class="fas fa-calendar-alt text-pink me-2"></i> 
                        {{ $featuredMovie->startYear ?? 'N/A' }}
                    </span>
                    <span>
                        <i class="fas fa-clock text-pink me-2"></i> 
                        {{ $featuredMovie->runtimeMinutes ?? 'N/A' }} min
                    </span>
                    <span style="color: white; font-weight: bold;">
                        <i class="fas fa-star text-pink me-1"></i> 
                        {{ number_format($featuredMovie->averageRating, 1) }}
                    </span>
                    <span class="text-muted" style="font-size: 0.9rem;">
                        ({{ number_format($featuredMovie->numVotes) }} votes)
                    </span>
                </div>

                <a href="{{ route('titles.show', $featuredMovie->tconst) }}" class="btn-hero">
                    <i class="fas fa-info-circle"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
@endif

<div class="container pb-5">

    {{-- 2. DAFTAR TOP 10 MOVIES --}}
    <div class="section-header">
        <h2 class="section-title">Top 10 Movies</h2>
    </div>

    <div class="movies-grid">
        @forelse ($topMovies as $movie)
            <a href="{{ route('titles.show', $movie->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    {{-- Placeholder Icon --}}
                    <i class="fas fa-film"></i>
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($movie->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>
                             <i class="fas fa-calendar me-1 text-pink" style="opacity: 0.7"></i>
                            {{ $movie->startYear ?? 'N/A' }}
                        </span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> 
                            {{ number_format($movie->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-film fa-3x mb-3" style="opacity: 0.3"></i>
                <p>Belum ada data film populer.</p>
            </div>
        @endforelse
    </div>

    {{-- 3. DAFTAR REKOMENDASI --}}
    <div class="section-header mt-5">
        <h2 class="section-title">✨ Direkomendasikan Untuk Anda</h2>
    </div>

    <div class="movies-grid">
        @forelse ($recommendedMovies as $movie)
            <a href="{{ route('titles.show', $movie->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($movie->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>
                            <i class="fas fa-calendar me-1 text-pink" style="opacity: 0.7"></i>
                            {{ $movie->startYear ?? 'N/A' }}
                        </span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> 
                            {{ number_format($movie->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-ticket-alt fa-3x mb-3" style="opacity: 0.3"></i>
                <p>Belum ada rekomendasi saat ini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
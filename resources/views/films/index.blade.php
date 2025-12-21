{{-- resources/views/films/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Dunia Film - Jelajahi Film Terpopuler')

@section('content')
<style>
    /* === KONFIGURASI WARNA (SAMA DENGAN TV SHOWS) === */
    :root {
        --bg-main: #0d0d0d;        /* Background Utama */
        --bg-card: #000000;        /* Card Background */
        --primary-pink: #d95f8c;   /* Pink (Highlight/Text) */
        --primary-red: #870339;    /* Burgundy (Button/Gradient) */
        --text-muted: #a3a3a3;     /* Abu-abu teks */
        --border-color: rgba(255, 255, 255, 0.15);
    }

    body {
        background-color: var(--bg-main);
        color: #ffffff;
        font-family: 'Poppins', sans-serif;
    }

    /* Utilities */
    .text-pink { color: var(--primary-pink) !important; }

    /* === HERO SECTION === */
    .hero-section {
        background: linear-gradient(to bottom, rgba(13, 13, 13, 0.85) 0%, var(--bg-main) 100%), 
                    url('https://source.unsplash.com/random/1920x1080/?cinema,movie,theater') no-repeat center center;
        background-size: cover;
        padding: 120px 0 80px;
        position: relative;
        margin-bottom: 50px;
        border-bottom: 1px solid var(--border-color);
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(90deg, #ffffff, var(--primary-pink));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: var(--text-muted);
        margin-bottom: 2.5rem;
        font-weight: 300;
    }

    /* === SEARCH FORM === */
    .search-input-group {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50px;
        padding: 5px;
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        display: flex;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .search-input-group:focus-within {
        border-color: var(--primary-pink);
        box-shadow: 0 0 15px rgba(217, 95, 140, 0.2);
    }

    .search-input {
        background: transparent;
        border: none;
        color: white;
        padding: 15px 25px;
        font-size: 1rem;
        width: 100%;
        flex-grow: 1;
    }
    .search-input:focus { outline: none; }
    .search-input::placeholder { color: rgba(255, 255, 255, 0.6); }

    .btn-custom {
        background: linear-gradient(90deg, var(--primary-red), var(--primary-pink));
        border: none;
        border-radius: 50px;
        color: white;
        padding: 12px 35px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-custom:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(217, 95, 140, 0.4);
        color: white;
    }

    /* === SECTION HEADERS === */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title::before {
        content: '';
        display: block;
        width: 5px;
        height: 24px;
        background: var(--primary-pink);
        border-radius: 2px;
    }

    /* === FILM CARDS (GRID) === */
    .films-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 50px;
    }

    .film-card {
        background: var(--bg-card);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .film-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary-pink);
        box-shadow: 0 10px 30px rgba(217, 95, 140, 0.15);
    }

    .film-card-image {
        height: 250px; /* Sedikit lebih tinggi untuk poster film */
        background: linear-gradient(135deg, #1a1a1a, #000000);
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }

    .film-card-image i {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.1);
        transition: 0.3s;
    }

    .film-card:hover .film-card-image i {
        color: var(--primary-pink);
        transform: scale(1.1);
    }

    .film-card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .film-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: white;
        line-height: 1.4;
    }

    .film-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .film-detail-btn {
        margin-top: auto;
        display: block;
        width: 100%;
        background: transparent;
        border: 1px solid var(--primary-pink);
        color: var(--primary-pink);
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .film-detail-btn:hover {
        background: var(--primary-pink);
        color: white;
        box-shadow: 0 0 15px rgba(217, 95, 140, 0.3);
    }

    /* === GENRE BADGES (GRID STYLE) === */
    .genres-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 15px;
        margin-bottom: 40px;
    }

    .genre-card {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 15px 10px;
        text-align: center;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .genre-card:hover {
        background: #1a1a1a;
        border-color: var(--primary-pink);
        transform: translateY(-3px);
    }

    .genre-icon {
        font-size: 1.5rem;
        color: var(--primary-pink);
        margin-bottom: 5px;
        opacity: 0.8;
    }
    
    .genre-name {
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* === SEARCH BAR BAWAH === */
    .bottom-search-section {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 16px;
        padding: 50px 20px;
        text-align: center;
        border: 1px dashed var(--border-color);
        margin-top: 30px;
    }

    .bottom-search-input {
        background: #000000;
        border: 1px solid var(--border-color);
        color: #ffffff;
        padding: 15px 25px;
        border-radius: 50px 0 0 50px;
        width: 100%;
        flex: 1; /* Agar sejajar */
        width: auto;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .bottom-search-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
        opacity: 1;
    }

    .bottom-search-input:focus {
        border-color: var(--primary-pink);
        outline: none;
        box-shadow: 0 0 15px rgba(217, 95, 140, 0.2);
    }
</style>

{{-- HERO SECTION --}}
<div class="hero-section">
    <div class="container text-center">
        <h1 class="hero-title">Jelajahi Dunia Film</h1>
        <p class="hero-subtitle">Temukan film bioskop, aktor, dan sutradara favorit Anda.</p>

        {{-- FORM PENCARIAN ATAS --}}
        <form action="{{ route('search') }}" method="GET" class="search-form mx-auto" style="max-width: 600px;">
            <div class="search-input-group">
                <input type="text" name="q" class="search-input" placeholder="Cari judul film, aktor, sutradara..." value="{{ request('q') }}">
                <button class="btn-custom" type="submit">
                    <i class="fas fa-search me-2"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container pb-5">

    {{-- SECTION: DAFTAR FILM TERPOPULER --}}
    <div class="section-header">
        <h2 class="section-title">🔥 Film Terpopuler</h2>
    </div>

    <div class="films-grid">
        @forelse ($topFilms as $film)
            <div class="film-card">
                {{-- Menggunakan Placeholder Icon agar seragam dengan desain TV --}}
                <div class="film-card-image">
                    <i class="fas fa-film"></i>
                </div>
                
                <div class="film-card-content">
                    <h5 class="film-title">{{ Str::limit($film->primaryTitle, 40) }}</h5>
                    
                    <div class="film-meta">
                        <span>
                            <i class="fas fa-calendar-alt me-1 text-pink"></i> 
                            {{ $film->startYear ?? 'N/A' }}
                        </span>
                        <span style="color: white; font-weight: bold;">
                            <i class="fas fa-star me-1 text-pink"></i>
                            {{ $film->averageRating ? number_format($film->averageRating, 1) : '-' }}
                        </span>
                    </div>

                    <a href="{{ route('titles.show', $film->tconst) }}" class="film-detail-btn">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12" style="grid-column: 1 / -1;">
                <div class="text-center py-5">
                    <i class="fas fa-video-slash fa-4x mb-4 text-muted"></i>
                    <h4 class="text-white mb-2">Belum ada data film terpopuler.</h4>
                </div>
            </div>
        @endforelse
    </div>

    {{-- SECTION: JELAJAHI GENRE --}}
    <div class="section-header mt-5">
        <h2 class="section-title">🎭 Jelajahi Genre</h2>
        <span class="badge bg-black border border-secondary text-muted">Kategori</span>
    </div>

    <div class="genres-grid">
        @forelse ($genres as $genre)
            <a href="{{ route('search', ['q' => $genre->genre_name]) }}" class="genre-card">
                <div class="genre-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="genre-name">{{ $genre->genre_name }}</div>
            </a>
        @empty
            <p class="text-muted col-12 text-center">Belum ada data genre.</p>
        @endforelse
    </div>

    {{-- SECTION: SEARCH BAR BAWAH --}}
    <div class="bottom-search-section">
        <h3 class="text-white mb-2" style="font-weight: 700;">Tidak menemukan yang Anda cari?</h3>
        <p class="text-muted mb-4">Cari judul film spesifik dari database lengkap kami.</p>
        
        <form action="{{ route('search') }}" method="GET">
            <div class="input-group d-flex" style="max-width: 500px; margin: 0 auto;">
                <input type="text" name="q" class="bottom-search-input" placeholder="Ketik judul film (misal: Avengers, Titanic)..." required>
                
                <button class="btn-custom" style="border-radius: 0 50px 50px 0; white-space: nowrap;" type="submit">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
            </div>
        </form>
    </div>

</div>

{{-- Script Loading Effect --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                    submitBtn.style.opacity = '0.8';
                    submitBtn.disabled = true;
                }
            });
        });
    });
</script>
@endsection
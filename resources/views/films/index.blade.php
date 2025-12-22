@extends('layouts.app')

@section('title', 'Dunia Film - Jelajahi Film Terpopuler')

@section('content')
<style>
    /* === KONFIGURASI WARNA === */
    :root {
        --bg-main: #0d0d0d;
        --bg-card: #000000;
        --primary-pink: #d95f8c;
        --primary-red: #870339;
        --text-muted: #a3a3a3;
        --border-color: rgba(255, 255, 255, 0.15);
    }

    body {
        background-color: var(--bg-main);
        color: #ffffff;
        font-family: 'Poppins', sans-serif;
    }

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
        margin-top: 60px; /* Jarak antar section */
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
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Ukuran kartu disesuaikan */
        gap: 24px;
        margin-bottom: 20px;
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
        height: 300px; /* Poster Height */
        background: linear-gradient(135deg, #1a1a1a, #000000);
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }

    .film-card-content {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .film-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: white;
        line-height: 1.4;
    }

    .film-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
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
        padding: 8px;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .film-detail-btn:hover {
        background: var(--primary-pink);
        color: white;
        box-shadow: 0 0 15px rgba(217, 95, 140, 0.3);
    }

    /* === GENRE BADGES === */
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

    .genre-icon { font-size: 1.5rem; color: var(--primary-pink); opacity: 0.8; }
    .genre-name { font-size: 0.9rem; font-weight: 600; }

    /* === BOTTOM SEARCH === */
    .bottom-search-section {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 16px;
        padding: 50px 20px;
        text-align: center;
        border: 1px dashed var(--border-color);
        margin-top: 50px;
    }
    .bottom-search-input {
        background: #000000;
        border: 1px solid var(--border-color);
        color: #ffffff;
        padding: 15px 25px;
        border-radius: 50px 0 0 50px;
        flex: 1; 
    }
</style>

{{-- HERO SECTION --}}
<div class="hero-section">
    <div class="container text-center">
        <h1 class="hero-title">Jelajahi Dunia Film</h1>
        <p class="hero-subtitle">Temukan film bioskop, aktor, dan sutradara favorit Anda.</p>
    </div>
</div>

<div class="container pb-5">

    {{-- 1. FILM TERPOPULER --}}
    <div class="section-header" style="margin-top: 0;">
        <h2 class="section-title">🔥 Film Terpopuler</h2>
    </div>

    <div class="films-grid">
        @forelse ($topFilms as $film)
            <div class="film-card">
                <div class="film-card-image">
                    {{-- POSTER IMAGE --}}
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $film->primaryTitle }}"
                         data-id="{{ $film->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="film-card-content">
                    <h5 class="film-title">{{ Str::limit($film->primaryTitle, 40) }}</h5>
                    <div class="film-meta">
                        <span><i class="fas fa-calendar-alt me-1 text-pink"></i> {{ $film->startYear ?? 'N/A' }}</span>
                        <span style="color: white; font-weight: bold;"><i class="fas fa-star me-1 text-pink"></i> {{ number_format($film->averageRating, 1) }}</span>
                    </div>
                    <a href="{{ route('titles.show', $film->tconst) }}" class="film-detail-btn">Lihat Detail</a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Belum ada data.</div>
        @endforelse
    </div>

{{-- SECTION: MOST WATCHED (BY VOTES) --}}
    <div class="section-header mt-5">
        <h2 class="section-title">⭐ Paling Banyak Ditonton (All Time)</h2>
    </div>

    <div class="films-grid">
        @forelse ($mostWatchedFilms as $film)
            <div class="film-card">
                <div class="film-card-image">
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $film->primaryTitle }}"
                         data-id="{{ $film->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="film-card-content">
                    <h5 class="film-title">{{ Str::limit($film->primaryTitle, 40) }}</h5>
                    <div class="film-meta">
                        <span><i class="fas fa-users me-1 text-pink"></i> {{ number_format($film->numVotes / 1000000, 1) }}M Votes</span>
                        <span style="font-weight: bold;"><i class="fas fa-star text-pink me-1"></i> {{ number_format($film->averageRating, 1) }}</span>
                    </div>
                    <a href="{{ route('titles.show', $film->tconst) }}" class="film-detail-btn">Lihat Detail</a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">Data tidak ditemukan.</div>
        @endforelse
    </div>

    {{-- 2. FILM TAHUN INI --}}
    <div class="section-header">
        <h2 class="section-title">📅 Film Musim Ini </h2>
    </div>
    <div class="films-grid">
        @forelse ($currentYearFilms as $film)
            <div class="film-card">
                <div class="film-card-image">
                    <img src="https://via.placeholder.com/300x450?text=Loading..." class="tmdb-poster" alt="{{ $film->primaryTitle }}" data-id="{{ $film->tconst }}" data-type="movie" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="film-card-content">
                    <h5 class="film-title">{{ Str::limit($film->primaryTitle, 40) }}</h5>
                    <div class="film-meta">
                        <span>{{ $film->startYear }}</span>
                        <span style="font-weight: bold;"><i class="fas fa-star text-pink me-1"></i> {{ number_format($film->averageRating, 1) }}</span>
                    </div>
                    <a href="{{ route('titles.show', $film->tconst) }}" class="film-detail-btn">Lihat Detail</a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Belum ada data tahun ini.</div>
        @endforelse
    </div>

{{-- SCRIPT LOAD GAMBAR TMDB --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // === API KEY TMDB ===
    const apiKey = '8e8ed515442c24035b99b36d4bbb8e6d'; 
    // ====================

    $('.tmdb-poster').each(function() {
        var imgElement = $(this);
        var id = imgElement.data('id');
        
        if (!id) return;

        // URL Find by IMDb ID
        var url = `https://api.themoviedb.org/3/find/${id}?api_key=${apiKey}&external_source=imdb_id`;

        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                var imagePath = null;
                
                // Prioritaskan hasil Movie karena ini halaman Film
                if (data.movie_results && data.movie_results.length > 0 && data.movie_results[0].poster_path) {
                    imagePath = data.movie_results[0].poster_path;
                } 
                // Jaga-jaga kalau ternyata datanya masuk sebagai TV/Episode
                else if (data.tv_results && data.tv_results.length > 0 && data.tv_results[0].poster_path) {
                    imagePath = data.tv_results[0].poster_path;
                }

                if (imagePath) {
                    imgElement.attr('src', 'https://image.tmdb.org/t/p/w500' + imagePath);
                } else {
                    // Placeholder kalau tidak ada gambar
                    imgElement.attr('src', 'https://via.placeholder.com/300x450/1a1a1a/555555?text=No+Poster');
                }
            },
            error: function() {
                console.log("Gagal load gambar: " + id);
            }
        });
    });
});
</script>
@endsection
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
    
    /* === HERO SECTION (SLIDESHOW) === */
    .hero-wrapper {
        position: relative;
        height: 85vh;
        min-height: 550px;
        overflow: hidden;
        
        /* Gradasi background yang akan ditimpa oleh gambar slide aktif */
        background: linear-gradient(135deg, var(--bg-main) 0%, #1a0510 50%, var(--primary-red) 100%);
        border-bottom: 1px solid var(--border-color);
    }

    .hero-slideshow {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .hero-slides {
        display: flex;
        height: 100%;
        transition: transform 0.8s ease-in-out;
    }

    .hero-slide {
        min-width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        position: relative;
        background-size: cover;
        background-position: center;
        transition: opacity 1s ease-in-out;
    }

    .hero-slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to right, rgba(13, 13, 13, 0.9) 0%, rgba(13, 13, 13, 0.7) 50%, rgba(13, 13, 13, 0.4) 100%);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 750px;
        padding-left: 15px;
    }

    /* Badge Khusus */
    .badge-featured {
        background: linear-gradient(90deg, var(--primary-red), var(--primary-pink));
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 1px;
        box-shadow: 0 5px 15px rgba(217, 95, 140, 0.3);
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

    /* Slide Indicators */
    .hero-indicators {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 3;
    }

    .indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .indicator.active {
        background-color: var(--primary-pink);
        transform: scale(1.2);
    }

    /* Navigation Arrows */
    .hero-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 3;
        transition: all 0.3s ease;
    }

    .hero-nav:hover {
        background-color: rgba(217, 95, 140, 0.7);
    }

    .hero-nav.prev {
        left: 20px;
    }

    .hero-nav.next {
        right: 20px;
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
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
        box-shadow: 0 20px 40px -10px rgba(217, 95, 140, 0.3);
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

{{-- 1. HERO PREVIEW SECTION (SLIDESHOW) --}}
@if (isset($featuredMovies) && count($featuredMovies) > 0)
    <div class="hero-wrapper">
        <div class="hero-slideshow">
            <div class="hero-slides">
                @foreach ($featuredMovies as $index => $movie)
                    <div class="hero-slide {{ $index == 0 ? 'active' : '' }}" 
                         data-index="{{ $index }}"
                         style="background-image: url('https://image.tmdb.org/t/p/original{{ $movie->backdrop_path ?? '' }}');">
                        <div class="hero-content">
                            <span class="badge badge-featured">
                                <i class="fas fa-crown me-2"></i> Featured Movie
                            </span>
                            
                            <h1 class="hero-title">{{ $movie->primaryTitle }}</h1>
                            
                            <div class="hero-meta">
                                <span>
                                    <i class="fas fa-calendar-alt text-pink me-2"></i> 
                                    {{ $movie->startYear ?? 'N/A' }}
                                </span>
                                <span>
                                    <i class="fas fa-clock text-pink me-2"></i> 
                                    {{ $movie->runtimeMinutes ?? 'N/A' }} min
                                </span>
                                <span style="color: white; font-weight: bold;">
                                    <i class="fas fa-star text-pink me-1"></i> 
                                    {{ number_format($movie->averageRating, 1) }}
                                </span>
                                <span class="text-muted" style="font-size: 0.9rem;">
                                    ({{ number_format($movie->numVotes) }} votes)
                                </span>
                            </div>

                            <a href="{{ route('titles.show', $movie->tconst) }}" class="btn-hero">
                                <i class="fas fa-info-circle"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Slide Indicators -->
            <div class="hero-indicators">
                @foreach ($featuredMovies as $index => $movie)
                    <div class="indicator {{ $index == 0 ? 'active' : '' }}" data-index="{{ $index }}"></div>
                @endforeach
            </div>
            
            <!-- Navigation Arrows -->
            <button class="hero-nav prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="hero-nav next">
                <i class="fas fa-chevron-right"></i>
            </button>
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
                    {{-- GANTI ICON DENGAN IMG INI --}}
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $movie->primaryTitle }}"
                         data-id="{{ $movie->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
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
        @forelse ($recommended as $rekomen)
            <a href="{{ route('titles.show', $rekomen->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    {{-- GANTI ICON DENGAN IMG INI --}}
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $rekomen->primaryTitle }}"
                         data-id="{{ $rekomen->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($rekomen->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>
                            <i class="fas fa-calendar me-1 text-pink" style="opacity: 0.7"></i>
                            {{ $rekomen->startYear ?? 'N/A' }}
                        </span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> 
                            {{ number_format($rekomen->averageRating, 1) }}
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

    {{-- ... (Setelah Section Rekomendasi) ... --}}

    {{-- 4. DAFTAR MUSIM INI (NEW THIS SEASON) --}}
    <div class="section-header mt-5">
        <h2 class="section-title">Rekomendasi Musim Ini</h2>
    </div>

    <div class="movies-grid">
        @forelse ($seasonalContent as $item)
            <a href="{{ route('titles.show', $item->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    {{-- Script JS kita otomatis baca data-type ini --}}
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $item->primaryTitle }}"
                         data-id="{{ $item->tconst }}" 
                         data-type="{{ $item->titleType == 'movie' ? 'movie' : 'tv' }}"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($item->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>
                             {{-- Ikon beda buat Movie vs TV --}}
                             @if($item->titleType == 'movie')
                                <i class="fas fa-film me-1 text-pink" style="opacity: 0.7"></i>
                             @else
                                <i class="fas fa-tv me-1 text-pink" style="opacity: 0.7"></i>
                             @endif
                            {{ $item->startYear ?? 'N/A' }}
                        </span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> 
                            {{ number_format($item->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <p>Belum ada data musim ini.</p>
            </div>
        @endforelse
    </div>

    {{-- ========================================================= --}}
    {{-- 1. GENRE: ACTION --}}
    {{-- ========================================================= --}}
    <div class="section-header mt-5">
        <h2 class="section-title">💥 Action Packed</h2>
    </div>

    <div class="movies-grid">
        @forelse ($actionMovies as $item)
            <a href="{{ route('titles.show', $item->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $item->primaryTitle }}"
                         data-id="{{ $item->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($item->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>
                            <i class="fas fa-calendar-alt me-1 text-pink" style="opacity: 0.7"></i>
                            {{ $item->startYear ?? 'N/A' }}
                        </span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> 
                            {{ number_format($item->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">Belum ada data Action.</div>
        @endforelse
    </div>
    {{-- ========================================================= --}}
    {{-- 3. GENRE: COMEDY --}}
    {{-- ========================================================= --}}
    <div class="section-header mt-5">
        <h2 class="section-title">😂 Comedy Central</h2>
    </div>

    <div class="movies-grid">
        @forelse ($ComedyMovies as $item)
            <a href="{{ route('titles.show', $item->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $item->primaryTitle }}"
                         data-id="{{ $item->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($item->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>
                            <i class="fas fa-calendar-alt me-1 text-pink" style="opacity: 0.7"></i>
                            {{ $item->startYear ?? 'N/A' }}
                        </span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> 
                            {{ number_format($item->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">Belum ada data Comedy.</div>
        @endforelse
    </div>

    {{-- ========================================================= --}}
    {{-- 3. GENRE: FAMILY --}}
    {{-- ========================================================= --}}
    <div class="section-header mt-5">
        <h2 class="section-title">👨‍👩‍👧‍👦 Tontonan Keluarga</h2>
    </div>

    <div class="movies-grid">
        @forelse ($familyMovies as $item)
            <a href="{{ route('titles.show', $item->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $item->primaryTitle }}"
                         data-id="{{ $item->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($item->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>{{ $item->startYear ?? 'N/A' }}</span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> {{ number_format($item->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">Belum ada data Family.</div>
        @endforelse
    </div>


    {{-- ========================================================= --}}
    {{-- 4. GENRE: ANIMATION --}}
    {{-- ========================================================= --}}
    <div class="section-header mt-5">
        <h2 class="section-title">🎨 Dunia Animasi</h2>
    </div>

    <div class="movies-grid">
        @forelse ($animationMovies as $item)
            <a href="{{ route('titles.show', $item->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $item->primaryTitle }}"
                         data-id="{{ $item->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($item->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>{{ $item->startYear ?? 'N/A' }}</span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> {{ number_format($item->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">Belum ada data Animasi.</div>
        @endforelse
    </div>


    {{-- ========================================================= --}}
    {{-- 5. GENRE: KIDS --}}
    {{-- ========================================================= --}}
    <div class="section-header mt-5">
        <h2 class="section-title">🧸 Kids Corner</h2>
    </div>

    <div class="movies-grid">
        @forelse ($kidsMovies as $item)
            <a href="{{ route('titles.show', $item->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $item->primaryTitle }}"
                         data-id="{{ $item->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($item->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>{{ $item->startYear ?? 'N/A' }}</span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> {{ number_format($item->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">Belum ada data Anak-anak.</div>
        @endforelse
    </div>
    {{-- ========================================================= --}}
    {{-- 2. GENRE: ROMANCE --}}
    {{-- ========================================================= --}}
    <div class="section-header mt-5">
        <h2 class="section-title">💖 Romance & Love</h2>
    </div>

    <div class="movies-grid">
        @forelse ($romanceMovies as $item)
            <a href="{{ route('titles.show', $item->tconst) }}" class="movie-card">
                <div class="movie-card-image">
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $item->primaryTitle }}"
                         data-id="{{ $item->tconst }}" 
                         data-type="movie"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="movie-card-content">
                    <h5 class="movie-title">{{ Str::limit($item->primaryTitle, 40) }}</h5>
                    <div class="movie-meta">
                        <span>{{ $item->startYear ?? 'N/A' }}</span>
                        <span style="color: white; font-weight: 600;">
                            <i class="fas fa-star text-pink me-1"></i> {{ number_format($item->averageRating, 1) }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">Belum ada data Romance.</div>
        @endforelse
    </div>


</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 $(document).ready(function() {
    // === API KEY TMDB (Punya kamu) ===
    const apiKey = '8e8ed515442c24035b99b36d4bbb8e6d'; 
    // =================================

    $('.tmdb-poster').each(function() {
        var imgElement = $(this);
        var id = imgElement.data('id');
        
        if (!id) return;

        // URL Find by External ID (IMDb ID)
        var url = `https://api.themoviedb.org/3/find/${id}?api_key=${apiKey}&external_source=imdb_id`;

        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                var imagePath = null;
                
                // --- PERBAIKAN LOGIKA DI SINI ---

                // 1. Cek Laci MOVIE (Poster)
                if (data.movie_results && data.movie_results.length > 0 && data.movie_results[0].poster_path) {
                    imagePath = data.movie_results[0].poster_path;
                }
                // 2. Cek Laci TV SHOW (Poster)
                else if (data.tv_results && data.tv_results.length > 0 && data.tv_results[0].poster_path) {
                    imagePath = data.tv_results[0].poster_path;
                }
                // 3. Cek Laci EPISODE (Still/Thumbnail) -> INI YANG KURANG TADI
                else if (data.tv_episode_results && data.tv_episode_results.length > 0 && data.tv_episode_results[0].still_path) {
                    imagePath = data.tv_episode_results[0].still_path;
                }

                // --- UPDATE SRC GAMBAR ---
                if (imagePath) {
                    // w500 = ukuran sedang
                    imgElement.attr('src', 'https://image.tmdb.org/t/p/w500' + imagePath);
                } else {
                    // Placeholder kalau gambar tidak ditemukan sama sekali
                    imgElement.attr('src', 'https://via.placeholder.com/300x450/1a1a1a/555555?text=No+Image');
                }
            },
            error: function() {
                console.log("Gagal load gambar untuk ID: " + id);
            }
        });
    });
    
    // === HERO SLIDESHOW FUNCTIONALITY ===
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator');
    const prevBtn = document.querySelector('.hero-nav.prev');
    const nextBtn = document.querySelector('.hero-nav.next');
    
    if (slides.length > 0) {
        let currentSlide = 0;
        let slideInterval;
        
        // Function to show a specific slide
        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));
            
            // Show the current slide
            slides[index].classList.add('active');
            indicators[index].classList.add('active');
            currentSlide = index;
        }
        
        // Function to show the next slide
        function nextSlide() {
            let newSlide = (currentSlide + 1) % slides.length;
            showSlide(newSlide);
        }
        
        // Function to show the previous slide
        function prevSlide() {
            let newSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(newSlide);
        }
        
        // Function to start the automatic slideshow
        function startSlideshow() {
            slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
        }
        
        // Function to stop the slideshow
        function stopSlideshow() {
            clearInterval(slideInterval);
        }
        
        // Event listeners for navigation buttons
        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopSlideshow();
            startSlideshow();
        });
        
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopSlideshow();
            startSlideshow();
        });
        
        // Event listeners for indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                showSlide(index);
                stopSlideshow();
                startSlideshow();
            });
        });
        
        // Pause slideshow on hover
        const slideshowContainer = document.querySelector('.hero-slideshow');
        slideshowContainer.addEventListener('mouseenter', stopSlideshow);
        slideshowContainer.addEventListener('mouseleave', startSlideshow);
        
        // Start the slideshow
        startSlideshow();
    }
});
</script>
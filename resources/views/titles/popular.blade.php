@extends('layouts.app')

@section('title', 'Popular Movies - Showfy')

@section('content')
<style>
    /* === PAGE SPECIFIC STYLES === */
    /* Menggunakan variabel global dari app.blade.php */

    /* Hero Section Khusus Page Ini */
    .page-hero {
        background: linear-gradient(to bottom, rgba(13, 13, 13, 0.9) 0%, var(--bg-main) 100%), 
                    url('https://source.unsplash.com/random/1920x600/?cinema,audience') no-repeat center center;
        background-size: cover;
        padding: 80px 0 60px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 50px;
    }

    .page-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 10px;
        background: linear-gradient(90deg, #fff, var(--primary-pink));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 1.1rem;
    }

    /* Grid System */
    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 60px;
    }

    /* Card Styling */
    .movie-card {
        background: var(--bg-card);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        position: relative;
    }

    .movie-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary-pink);
        box-shadow: 0 10px 30px rgba(217, 95, 140, 0.15);
    }

    .movie-card-image {
        height: 250px;
        background: linear-gradient(135deg, #1a1a1a, #000000);
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border-color);
    }

    .movie-card-image i {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.1);
        transition: 0.3s;
    }

    /* Ubah warna ikon jadi api merah/pink saat hover */
    .movie-card:hover .movie-card-image i {
        color: var(--primary-pink);
        transform: scale(1.1);
        filter: drop-shadow(0 0 10px var(--primary-red));
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
        color: white;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .movie-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    /* Badge Popularity Score */
    .score-badge {
        background: rgba(217, 95, 140, 0.1);
        color: var(--primary-pink);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid rgba(217, 95, 140, 0.2);
    }
</style>

{{-- HERO SECTION --}}
<div class="page-hero">
    <div class="container">
        <h1 class="page-title">🔥 Popular Movies</h1>
        <p class="page-subtitle">Film paling banyak ditonton dan dicari saat ini.</p>
    </div>
</div>

<div class="container pb-5">

    {{-- Alert Error --}}
    @if(isset($error))
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>{{ $error }}</div>
        </div>
    @endif

    {{-- MOVIES GRID --}}
    <div class="movies-grid">
        @foreach($popular as $movie)
            <a href="{{ route('titles.show', $movie->tconst) }}" class="movie-card">
                
                {{-- Image Placeholder --}}
                <div class="movie-card-image">
                    {{-- Ikon Api untuk nuansa "Popular" --}}
                    <i class="fas fa-fire"></i>
                </div>

                <div class="movie-card-content">
                    {{-- Title --}}
                    <h5 class="movie-title">
                        {{ Str::limit($movie->primaryTitle, 40) }}
                    </h5>

                    {{-- Meta Info --}}
                    <div class="movie-meta">
                        <span>
                            <i class="fas fa-calendar-alt me-1"></i> 
                            {{ $movie->startYear ?? 'N/A' }}
                        </span>
                        
                        @if(isset($movie->popularityScore))
                            <span class="score-badge">
                                <i class="fas fa-chart-line me-1"></i> 
                                {{ number_format($movie->popularityScore) }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- State Kosong --}}
    @if(count($popular) == 0 && !isset($error))
        <div class="text-center py-5 text-muted">
            <i class="fas fa-film fa-3x mb-3 opacity-50"></i>
            <p>Belum ada data film populer.</p>
        </div>
    @endif

</div>
@endsection
@extends('layouts.app')

@section('title', 'Hasil Pencarian - ' . ($keyword ?? 'Semua'))

@section('content')
<style>
    /* === SEARCH PAGE STYLES (UPDATED THEME) === */
    :root {
        --primary-pink: #d95f8c;
        --bg-main: #0d0d0d;
        --bg-card: #1a1a1a;
        --border-color: #333;
        --text-muted: #a3a3a3;
    }
    
    .search-header {
        padding: 60px 0 40px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 40px;
        background: linear-gradient(to bottom, rgba(135, 3, 57, 0.3) 0%, var(--bg-main) 100%);
    }

    .search-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .search-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 24px;
        margin-bottom: 60px;
    }

    .result-card {
        background: var(--bg-card);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(217, 95, 140, 0.2);
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: white;
    }

    .result-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary-pink);
        box-shadow: 0 10px 40px rgba(217, 95, 140, 0.3);
    }

    .result-card-image {
        height: 280px; /* Rasio poster yang lebih pas */
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--border-color);
    }

    .result-card-image i {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.1);
    }

    .tmdb-poster {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .result-card:hover .tmdb-poster {
        transform: scale(1.1);
    }

    .result-card-content {
        padding: 18px;
        flex-grow: 1;
    }

    .result-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: white;
    }

    .type-badge {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        border: 1px solid var(--primary-pink);
        color: var(--primary-pink);
        padding: 3px 10px;
        border-radius: 50px;
    }
    
    .text-pink { color: var(--primary-pink) !important; }
</style>

<div class="search-header">
    <div class="container">
        @if($keyword)
            <h1 class="search-title">
                Hasil Pencarian: <span class="text-pink">{{ $keyword }}</span>
            </h1>
        @else
            <h1 class="search-title">Pencarian</h1>
        @endif

        @if(isset($results) && count($results) > 0)
            <p class="search-subtitle">
                Ditemukan <strong class="text-white">{{ count($results) }}</strong> hasil yang cocok.
            </p>
        @endif
    </div>
</div>

<div class="container pb-5">
    @if(isset($results) && count($results) > 0)
        <div class="search-grid">
            @foreach($results as $title)
                {{-- PENTING: data-tconst harus ada agar JS bisa ambil gambar --}}
                <a href="{{ route('titles.show', $title->tconst) }}" class="result-card" data-tconst="{{ $title->tconst }}">
                    
                    <div class="result-card-image">
                        {{-- Elemen gambar TMDB --}}
                        <img class="tmdb-poster" style="display: none;" src="" alt="{{ $title->primaryTitle }}">
                        
                        {{-- Ikon Fallback --}}
                        <div class="poster-icon">
                            @if(Str::contains(strtolower($title->titleType), 'movie'))
                                <i class="fas fa-film"></i>
                            @elseif(Str::contains(strtolower($title->titleType), 'tv'))
                                <i class="fas fa-tv"></i>
                            @else
                                <i class="fas fa-video"></i>
                            @endif
                        </div>
                    </div>

                    <div class="result-card-content">
                        <h5 class="result-title">{{ Str::limit($title->primaryTitle, 50) }}</h5>
                        <div class="d-flex align-items: center; justify-content: space-between; font-size: 0.85rem;">
                            <span class="text-muted">
                                <i class="fas fa-calendar-alt me-1 text-pink"></i> {{ $title->startYear ?? 'N/A' }}
                            </span>
                            <span class="type-badge">
                                {{ $title->titleType == 'movie' ? 'Film' : ($title->titleType == 'tvSeries' ? 'Series' : 'Video') }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-search fa-5x mb-3" style="color: #333;"></i>
            <h3 class="text-white">Oops, tidak ada hasil.</h3>
            <a href="{{ url('/') }}" class="btn btn-link text-pink">Kembali ke Beranda</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const apiKey = '8e8ed515442c24035b99b36d4bbb8e6d'; 
        const resultCards = document.querySelectorAll('.result-card');

        resultCards.forEach(card => {
            const tconst = card.getAttribute('data-tconst');
            const posterImg = card.querySelector('.tmdb-poster');
            const posterIcon = card.querySelector('.poster-icon');

            if (tconst && tconst.startsWith('tt')) {
                const url = `https://api.themoviedb.org/3/find/${tconst}?api_key=${apiKey}&external_source=imdb_id`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        const result = data.movie_results[0] || data.tv_results[0] || data.tv_episode_results[0];

                        if (result && (result.poster_path || result.still_path)) {
                            const path = result.poster_path || result.still_path;
                            posterImg.src = `https://image.tmdb.org/t/p/w500${path}`;
                            posterImg.style.display = 'block';
                            if(posterIcon) posterIcon.style.display = 'none';
                        }
                    })
                    .catch(err => console.error("Error TMDB:", err));
            }
        });
    });
</script>
@endsection
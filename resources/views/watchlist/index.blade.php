@extends('layouts.app')

@section('title', 'My Watchlist - Showfy')

@section('content')
<style>
    .page-header {
        background: linear-gradient(to bottom, rgba(135, 3, 57, 0.4) 0%, var(--bg-main) 100%);
        padding: 60px 0 30px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 40px;
    }
    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 24px;
    }
    /* Menggunakan style card dari global theme (app.blade.php) */
    .movie-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        transition: 0.3s;
        text-decoration: none;
        color: white;
        display: block;
        height: 100%;
    }
    .movie-card:hover {
        transform: translateY(-5px);
        border-color: var(--c-rose);
        box-shadow: 0 10px 30px rgba(217, 95, 140, 0.2);
    }
    .card-img-placeholder {
        height: 280px;
        background: linear-gradient(135deg, #222, #000);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.1);
        font-size: 3rem;
    }
    .movie-card:hover .card-img-placeholder { color: var(--c-rose); }
</style>

<div class="page-header text-center">
    <div class="container">
        <h1 class="fw-bold display-5 mb-2">My Watchlist</h1>
        <p class="text-muted">Daftar film dan serial yang ingin Anda tonton.</p>
    </div>
</div>

<div class="container pb-5">
    @if(count($movies) > 0)
        <div class="movies-grid">
            @foreach($movies as $movie)
                <a href="{{ route('titles.show', $movie->tconst) }}" class="movie-card">
                    <div class="card-img-placeholder">
                        @if(isset($movie->titleType) && $movie->titleType == 'movie')
                            <i class="fas fa-film"></i>
                        @else
                            <i class="fas fa-tv"></i>
                        @endif
                    </div>
                    <div class="p-3">
                        <h6 class="fw-bold mb-1 text-white text-truncate">{{ $movie->primaryTitle }}</h6>
                        <div class="d-flex justify-content-between small text-muted">
                            <span>{{ $movie->startYear ?? 'N/A' }}</span>
                            <span>{{ $movie->runtimeMinutes ? $movie->runtimeMinutes.' min' : '' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="far fa-bookmark fa-4x mb-3 text-muted opacity-25"></i>
            <h3 class="fw-bold">Watchlist Kosong</h3>
            <p class="text-muted">Anda belum menambahkan film apapun ke daftar tontonan.</p>
            <a href="{{ route('films.index') }}" class="btn btn-gradient mt-3">Jelajahi Film</a>
        </div>
    @endif
</div>
@endsection
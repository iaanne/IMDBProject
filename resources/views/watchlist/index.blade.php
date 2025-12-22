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
    .card-img-container {
        height: 300px;
        background: #111;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .tmdb-poster {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s;
    }
    .fallback-icon {
        color: rgba(255,255,255,0.1);
        font-size: 3rem;
        position: absolute;
    }
    .movie-card:hover .fallback-icon { color: var(--c-rose); }
</style>

<div class="page-header text-center">
    <div class="container">
        <h1 class="fw-bold display-5 mb-2">My Watchlist</h1>
        <p class="text-white-muted">Daftar film dan serial yang ingin Anda tonton.</p>
    </div>
</div>

<div class="container pb-5">
    @if(count($movies) > 0)
        <div class="movies-grid">
            @foreach($movies as $movie)
                <a href="{{ route('titles.show', $movie->tconst) }}" class="movie-card">
                    <div class="card-img-container">
                        <div class="fallback-icon">
                            @if(isset($movie->titleType) && $movie->titleType == 'movie')
                                <i class="fas fa-film"></i>
                            @else
                                <i class="fas fa-tv"></i>
                            @endif
                        </div>
                        
                        <img src="" 
                             class="tmdb-poster" 
                             style="display: none;" 
                             data-title="{{ $movie->primaryTitle }}" 
                             data-year="{{ $movie->startYear }}">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Ganti API_KEY ini dengan key milikmu dari themoviedb.org
    const API_KEY = '8e8ed515442c24035b99b36d4bbb8e6d'; 

    $('.tmdb-poster').each(function() {
        const $img = $(this);
        const title = $img.data('title');
        const year = $img.data('year');
        const $fallback = $img.siblings('.fallback-icon');

        // Gunakan Multi Search agar bisa cari Movie sekaligus TV Series
        const url = `https://api.themoviedb.org/3/search/multi?api_key=${API_KEY}&query=${encodeURIComponent(title)}&year=${year}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.results && data.results.length > 0) {
                    // Ambil hasil pertama yang punya poster
                    const match = data.results.find(item => item.poster_path);
                    if (match) {
                        $img.attr('src', `https://image.tmdb.org/t/p/w500${match.poster_path}`);
                        $img.fadeIn(); // Muncul perlahan
                        $fallback.hide(); // Sembunyikan icon kotak hitam
                    }
                }
            })
            .catch(err => console.error("Error TMDB:", err));
    });
});
</script>
@endsection
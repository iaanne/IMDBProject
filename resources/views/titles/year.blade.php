@extends('layouts.app')

@section('title', 'Movies Released in ' . $year)

@section('content')
<style>
    /* === YEAR PAGE STYLES === */
    
    /* Header & Form Area */
    .year-header {
        background: linear-gradient(to bottom, rgba(13, 13, 13, 0.9), var(--bg-main)),
                    url('https://source.unsplash.com/random/1920x600/?calendar,history');
        background-size: cover;
        background-position: center;
        padding: 80px 0 40px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 50px;
    }

    .page-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(90deg, #fff, var(--primary-pink));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Form Filter Tahun Kustom */
    .year-form-container {
        max-width: 400px;
        margin: 0 auto;
        position: relative;
    }

    .year-input-group {
        display: flex;
        background: rgba(0, 0, 0, 0.6);
        border: 1px solid var(--border-color);
        border-radius: 50px;
        padding: 5px;
        backdrop-filter: blur(10px);
        transition: 0.3s;
    }

    .year-input-group:focus-within {
        border-color: var(--primary-pink);
        box-shadow: 0 0 15px rgba(217, 95, 140, 0.2);
    }

    .year-input {
        background: transparent;
        border: none;
        color: white;
        padding: 10px 20px;
        width: 100%;
        font-size: 1.1rem;
        text-align: center;
    }

    .year-input:focus { outline: none; }
    
    /* Hilangkan panah spinner pada input number */
    .year-input::-webkit-outer-spin-button,
    .year-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .btn-filter {
        background: linear-gradient(90deg, var(--primary-red), var(--primary-pink));
        border: none;
        border-radius: 50px;
        color: white;
        padding: 10px 30px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-filter:hover {
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(217, 95, 140, 0.4);
    }

    /* Grid System (Konsisten) */
    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
        color: white;
    }

    .movie-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary-pink);
        box-shadow: 0 10px 30px rgba(217, 95, 140, 0.15);
    }

    .movie-card-image {
        height: 240px;
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

    .movie-card:hover .movie-card-image i {
        color: var(--primary-pink);
        transform: scale(1.1);
    }

    .movie-card-content {
        padding: 18px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .movie-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.4;
        color: white;
    }

    .movie-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: auto;
        display: flex;
        align-items: center;
    }
</style>

{{-- HEADER SECTION --}}
<div class="year-header">
    <div class="container">
        <h1 class="page-title">
            Archive: <span class="text-pink">{{ $year }}</span>
        </h1>
        <p class="text-muted mb-4">Menampilkan film yang dirilis pada tahun {{ $year }}</p>

        {{-- FORM FILTER TAHUN --}}
        <form method="GET" action="{{ route('titles.byYear') }}" class="year-form-container">
            <div class="year-input-group">
                <input type="number" name="year" placeholder="Ganti Tahun..." 
                       class="year-input" min="1800" max="2100" value="{{ $year }}" required>
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container pb-5">

    {{-- Error Handling --}}
    @if(isset($error))
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $error }}
        </div>
    @endif

    {{-- Empty State --}}
    @if(isset($movies) && count($movies) === 0)
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-4x mb-3" style="color: #334155;"></i>
            <h3 class="text-white fw-bold">Tidak ada film ditemukan</h3>
            <p class="text-muted">Kami tidak menemukan film untuk tahun {{ $year }}.</p>
        </div>
    @endif

    {{-- LIST FILM (GRID) --}}
    @if(isset($movies) && count($movies) > 0)
        <div class="movies-grid">
            @foreach($movies as $movie)
                <a href="{{ route('titles.show', $movie->tconst) }}" class="movie-card">
                    
                    <div class="movie-card-image">
                        <i class="fas fa-film"></i>
                    </div>

                    <div class="movie-card-content">
                        <h5 class="movie-title">
                            {{ Str::limit($movie->primaryTitle, 40) }}
                        </h5>

                        <div class="movie-meta">
                            <span>
                                <i class="fas fa-clock me-1 text-pink"></i> 
                                {{ $movie->runtimeMinutes ? $movie->runtimeMinutes . ' min' : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection
@extends('layouts.app')

@section('title', 'Hasil Pencarian - ' . ($keyword ?? 'Semua'))

@section('content')
<style>
    /* === SEARCH PAGE STYLES (UPDATED THEME) === */
    
    /* Header Pencarian dengan sentuhan Pink */
    .search-header {
        padding: 60px 0 40px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 40px;
        /* Gradient pink gelap ke hitam */
        background: linear-gradient(to bottom, rgba(135, 3, 57, 0.3) 0%, var(--bg-main) 100%);
    }

    .search-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .search-subtitle {
        color: var(--text-muted);
        font-size: 1.1rem;
    }

    /* Grid System */
    .search-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 24px;
        margin-bottom: 60px;
    }

    /* Card Styling */
    .result-card {
        background: var(--bg-card);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        /* Border default sedikit pink transparan */
        border: 1px solid rgba(217, 95, 140, 0.2);
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: white;
        position: relative;
    }

    /* Efek Hover lebih kuat */
    .result-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary-pink);
        /* Glow pink lebih terang */
        box-shadow: 0 10px 40px rgba(217, 95, 140, 0.3);
    }

    .result-card-image {
        height: 220px;
        background: linear-gradient(135deg, #1a1a1a, #000000);
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border-color);
    }

    .result-card-image i {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.1);
        transition: 0.3s;
    }

    .result-card:hover .result-card-image i {
        color: var(--primary-pink);
        transform: scale(1.1);
        filter: drop-shadow(0 0 8px var(--primary-pink));
    }

    .result-card-content {
        padding: 18px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .result-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
        color: white;
    }

    .result-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Type Badge (FILM/SERIES) - Tema Pink */
    .type-badge {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: transparent;
        /* Border dan teks pink */
        border: 1px solid var(--primary-pink);
        color: var(--primary-pink);
        padding: 3px 10px;
        border-radius: 50px;
    }
    
    /* Helper untuk warna ikon */
    .text-pink {
        color: var(--primary-pink) !important;
    }
</style>

{{-- SEARCH HEADER SECTION --}}
<div class="search-header">
    <div class="container">
        @if($keyword)
            <h1 class="search-title">
                <span class="text-pink">Hasil Pencarian: <span class="text-pink">{{ $keyword }}</span>
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

    {{-- ERROR MESSAGE --}}
    @if(isset($error))
        <div class="alert alert-danger d-flex align-items-center mb-4" style="background: rgba(220, 53, 69, 0.1); border-color: #dc3545; color: #ff8a93;">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ $error }}
        </div>
    @endif

    {{-- NO RESULTS STATE --}}
    @if(isset($results) && count($results) === 0)
        <div class="text-center py-5 my-5">
            <div class="mb-4">
                <i class="fas fa-search fa-5x" style="color: var(--border-color);"></i>
            </div>
            <h3 class="text-white fw-bold mb-3">Oops, tidak ada hasil.</h3>
            <p class="text-muted mb-4">Kami tidak dapat menemukan apa yang Anda cari. Coba kata kunci lain.</p>
            
            <a href="{{ url('/') }}" class="btn btn-outline-light px-4 py-2" style="border-radius: 50px; border-color: var(--primary-pink); color: var(--primary-pink);">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>
    @endif

    {{-- SEARCH RESULTS GRID --}}
    @if(isset($results) && count($results) > 0)
        
        <div class="search-grid">
            @foreach($results as $title)
                <a href="{{ route('titles.show', $title->tconst) }}" class="result-card">
                    
                    <div class="result-card-image">
                        {{-- Logika Ikon berdasarkan Tipe --}}
                        @if(Str::contains(strtolower($title->titleType), 'movie'))
                            <i class="fas fa-film"></i>
                        @elseif(Str::contains(strtolower($title->titleType), 'tv'))
                            <i class="fas fa-tv"></i>
                        @else
                            <i class="fas fa-video"></i>
                        @endif
                    </div>

                    <div class="result-card-content">
                        <h5 class="result-title">{{ Str::limit($title->primaryTitle, 50) }}</h5>

                        <div class="result-meta mb-3">
                            <span>
                                {{-- Ikon Kalender Pink --}}
                                <i class="fas fa-calendar-alt me-2 text-pink"></i> 
                                {{ $title->startYear ?? 'N/A' }}
                            </span>
                            
                            {{-- Tampilkan Badge Tipe Pink --}}
                            <span class="type-badge">
                                {{ $title->titleType == 'movie' ? 'Film' : ($title->titleType == 'tvSeries' ? 'Series' : 'Video') }}
                            </span>
                        </div>

                        @if($title->runtimeMinutes)
                            <div class="text-muted small">
                                {{-- Ikon Jam Pink --}}
                                <i class="fas fa-clock me-2 text-pink"></i> {{ $title->runtimeMinutes }} min
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

    @endif

</div>
@endsection
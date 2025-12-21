@extends('layouts.app')

@section('content')
<style>
    /* === 1. CARD STYLING (Supaya Rapi & Konsisten) === */
    .shows-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 25px;
        padding-top: 10px;
    }

    .show-card {
        background: #141414;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .show-card:hover {
        transform: translateY(-8px);
        border-color: #d95f8c; /* Warna Pink Rose */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
    }

    .show-card-image {
        position: relative;
        aspect-ratio: 2/3;
        background: #1a1a1a;
        overflow: hidden;
    }

    .show-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .show-card:hover .show-card-image img {
        transform: scale(1.1);
    }

    .show-card-content {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .show-title {
        font-size: 1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 10px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .rating-badge {
        background: rgba(255, 255, 255, 0.05);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #fbbf24; /* Gold */
    }
    
    .genre-result-header {
        margin-bottom: 40px;
        padding-top: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 20px;
    }

    .text-pink {
        color: #d95f8c !important;
        text-decoration: none;
    }
</style>

<div class="container py-5">
    {{-- Header & Breadcrumb --}}
    <div class="genre-result-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('genres.index') }}" class="text-pink">Genre</a></li>
                <li class="breadcrumb-item active text-white opacity-50" aria-current="page">{{ $name }}</li>
            </ol>
        </nav>
        <h2 class="text-white fw-bold">Kategori: <span class="text-pink">{{ $name }}</span></h2>
    </div>

    {{-- Grid Card --}}
    <div class="shows-grid">
        @forelse($titles as $title)
            @php
                $digits = preg_replace('/[^0-9]/', '', $title->tconst);
                $finalId = 'tt' . str_pad($digits, 7, '0', STR_PAD_LEFT);
            @endphp
            
            <a href="{{ route('titles.show', $finalId) }}" class="show-card" style="text-decoration: none; color: inherit;">
                <div class="show-card-image">
                    {{-- Placeholder sementara sebelum diganti JS --}}
                    <img src="https://via.placeholder.com/300x450?text=Loading..." 
                         class="tmdb-poster" 
                         alt="{{ $title->primaryTitle }}"
                         data-id="{{ $finalId }}" 
                         data-type="movie"
                         loading="lazy">
                </div>
                <div class="show-card-content">
                    <h3 class="show-title">{{ Str::limit($title->primaryTitle, 40) }}</h3>
                    <div class="show-meta">
                        <div class="rating-badge">
                            <i class="fas fa-star"></i>
                            {{ number_format($title->averageRating ?? 0, 1) }}
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-white opacity-50">Tidak ada tontonan ditemukan untuk genre ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // GANTI INI dengan API Key TMDB kamu
        const apiKey = '8e8ed515442c24035b99b36d4bbb8e6d'; 
        
        const posters = document.querySelectorAll('.tmdb-poster');
        
        posters.forEach(img => {
            const contentId = img.dataset.id;
            
            if (contentId) {
                // Gunakan endpoint 'find' untuk mencari berdasarkan IMDb ID (tconst)
                const url = `https://api.themoviedb.org/3/find/${contentId}?api_key=${apiKey}&external_source=imdb_id`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let result = null;
                        
                        // Cek movie_results atau tv_results
                        if (data.movie_results && data.movie_results.length > 0) {
                            result = data.movie_results[0];
                        } else if (data.tv_results && data.tv_results.length > 0) {
                            result = data.tv_results[0];
                        }

                        if (result && result.poster_path) {
                            img.src = `https://image.tmdb.org/t/p/w342${result.poster_path}`;
                        } else {
                            img.src = `https://via.placeholder.com/300x450?text=No+Poster`;
                        }
                    })
                    .catch(err => {
                        console.error("TMDB Error:", err);
                        img.src = `https://via.placeholder.com/300x450?text=Error`;
                    });
            }
        });
    });
</script>
@endsection
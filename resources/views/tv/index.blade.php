@extends('layouts.app')

@section('title', 'Beranda TV Show - Temukan Serial Terbaik')

@section('content')
<style>
    /* === KONFIGURASI WARNA PREMIUM (Sesuai Dashboard) === */
    :root {
        --bg-main: #0d0d0d;        
        --bg-card: #141414;        /* Ubah jadi abu gelap mewah (bukan hitam pekat) */
        --primary-pink: #d95f8c;   
        --primary-red: #870339;    
        --text-muted: #a3a3a3;     
        --border-color: rgba(255, 255, 255, 0.1); 
    }

    body {
        background-color: var(--bg-main);
        color: #ffffff;
        font-family: 'Outfit', 'Poppins', sans-serif;
    }

    .text-pink { color: var(--primary-pink) !important; }

    /* === HERO SECTION === */
    .hero-section {
        background: linear-gradient(to bottom, rgba(13, 13, 13, 0.85) 0%, var(--bg-main) 100%), 
                    url('https://source.unsplash.com/random/1920x1080/?television,series,netflix') no-repeat center center;
        background-size: cover;
        padding: 120px 0 80px;
        position: relative;
        margin-bottom: 50px;
        border-bottom: 1px solid var(--border-color);
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
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

    /* === SEARCH BAR === */
    .search-input-group {
        background: rgba(20, 20, 20, 0.8); /* Glass effect */
        border-radius: 50px;
        padding: 8px;
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        display: flex;
        transition: all 0.3s;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .search-input-group:focus-within {
        border-color: var(--primary-pink);
        box-shadow: 0 0 20px rgba(217, 95, 140, 0.2);
        background: #1a1a1a;
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
    .search-input::placeholder { color: rgba(255, 255, 255, 0.5); }

    .btn-custom {
        background: linear-gradient(135deg, var(--primary-red), var(--primary-pink));
        border: none;
        border-radius: 50px;
        color: white;
        padding: 12px 35px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(135, 3, 57, 0.3);
    }
    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(217, 95, 140, 0.4);
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
        gap: 12px;
    }

    .section-title i {
        color: var(--primary-pink);
        background: rgba(217, 95, 140, 0.1);
        padding: 8px;
        border-radius: 8px;
        font-size: 1.2rem;
    }

    /* === SHOW CARDS (PREMIUM STYLE) === */
    .shows-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 50px;
    }

    .show-card {
        background: var(--bg-card);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border-color);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .show-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary-pink);
        box-shadow: 0 15px 40px rgba(217, 95, 140, 0.1);
    }

    .show-card-image {
    /* Gunakan aspect ratio portrait standar poster film (2:3) */
    aspect-ratio: 2 / 3; 
    height: auto; /* Biarkan tinggi menyesuaikan rasio */
    background: #222;
    position: relative;
    overflow: hidden;
}
    
    .show-card-image::after {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 50%; height: 100%;
        background: linear-gradient(to right, transparent, rgba(255,255,255,0.05), transparent);
        transform: skewX(-25deg);
        transition: 0.5s;
    }
    .show-card:hover .show-card-image::after { left: 150%; }

    .show-card-image {
        width: 100%;
        /* Hapus height: 200px */
        aspect-ratio: 2 / 3; 
        background: #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
    }

    .tmdb-poster {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Memastikan gambar memenuhi kotak tanpa gepeng */
        display: block;
    }
    .show-card:hover .show-card-image i { color: var(--primary-pink); transform: scale(1.1); }

    .show-card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .show-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: white;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .show-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .show-detail-btn {
        margin-top: auto;
        display: block;
        width: 100%;
        background: transparent;
        border: 1px solid var(--primary-pink);
        color: var(--primary-pink);
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .show-detail-btn:hover {
        background: var(--primary-pink);
        color: white;
        box-shadow: 0 5px 15px rgba(217, 95, 140, 0.3);
        transform: translateY(-2px);
    }

    /* === NETWORK CARDS === */
    .networks-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 15px;
        margin-bottom: 40px;
    }

    .network-card {
        background: var(--bg-card);
        border-radius: 12px;
        padding: 20px 10px;
        text-align: center;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .network-card:hover {
        background: #1a1a1a;
        border-color: var(--primary-pink);
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .network-icon {
        width: 50px;
        height: 50px;
        background: rgba(217, 95, 140, 0.1); 
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: var(--primary-pink);
        margin-bottom: 5px;
        transition: 0.3s;
    }
    .network-card:hover .network-icon { background: var(--primary-pink); color: white; }

    .network-name { font-size: 0.9rem; font-weight: 600; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* === SEARCH BAR BAWAH === */
    .network-search-section {
        background: linear-gradient(135deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
        border-radius: 20px;
        padding: 50px 20px;
        text-align: center;
        border: 1px dashed var(--border-color);
        margin-top: 40px;
    }

    .network-search-input {
        background: #000000; 
        border: 1px solid var(--border-color);
        color: #ffffff; 
        padding: 15px 25px;
        border-radius: 50px 0 0 50px;
        flex: 1;      
        width: auto;   
        font-size: 1rem;
        transition: all 0.3s;
    }
    .network-search-input::placeholder { color: rgba(255, 255, 255, 0.5); }
    .network-search-input:focus { border-color: var(--primary-pink); outline: none; box-shadow: 0 0 15px rgba(217, 95, 140, 0.2); }

    .tmdb-poster {
    width: 100%;
    height: 100%;
    /* object-fit: cover memastikan gambar memenuhi kotak tanpa menjadi gepeng */
    object-fit: cover; 
    display: block;

    /* Hilangkan garis bawah dan atur warna teks untuk link card */
a.show-card {
    text-decoration: none !important; /* Menghilangkan garis bawah */
    color: inherit; /* Mengikuti warna teks asli (putih) */
    display: flex;
    flex-direction: column;
}

/* Pastikan judul tidak berubah warna saat di-hover */
a.show-card:hover .show-title {
    color: var(--primary-pink); /* Opsional: Ubah judul jadi pink saat hover agar lebih premium */
    text-decoration: none;
}

/* Hilangkan garis biru pada link K-Drama juga */
.shows-grid a {
    text-decoration: none;
}
}
</style>

{{-- HERO SECTION --}}
<div class="hero-section">
    <div class="hero-content text-center container">
        <h1 class="hero-title">Dunia Serial TV</h1>
        <p class="hero-subtitle">Temukan serial TV terbaik dari berbagai genre dan jaringan.</p>
    
    </div>
</div>

<div class="container pb-5">
    <div class="section-header mt-5">
    <h2 class="section-title">
        <i class="fas fa-star"></i> Paling Popular
    </h2>
</div>
  <div class="shows-grid">
    @foreach($topShows as $show)
        @php
            $rawId = $show->tconst ?? $show->show_id ?? '';
            $digits = preg_replace('/[^0-9]/', '', $rawId);
            $finalId = !empty($digits) ? 'tt' . str_pad($digits, 7, '0', STR_PAD_LEFT) : null;
        @endphp
        
        @if($finalId)
            <a href="{{ route('titles.show', $finalId) }}" class="show-card" style="text-decoration: none;">
        @else
            <div class="show-card" style="opacity: 0.7; cursor: not-allowed;">
        @endif
        
            {{-- Tambahkan class placeholder-bg di sini --}}
            <div class="show-card-image placeholder-bg">
                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                     class="tmdb-poster" 
                     alt="{{ $show->primaryTitle ?? 'Show' }}"
                     data-id="{{ $finalId }}" 
                     data-type="tv"
                     onerror="this.style.opacity='0'"> {{-- Jika error, sembunyikan gambar --}}
            </div>
            
            <div class="show-card-content">
                <h3 class="show-title">{{ $show->primaryTitle ?? $show->name }}</h3>
                <div class="show-meta">
                    <span><i class="fas fa-star text-pink"></i> {{ number_format($show->averageRating ?? 0, 1) }}</span>
                    <span><i class="fas fa-eye text-pink"></i> {{ number_format(($show->numVotes ?? 0) / 1000, 1) }}K</span>
                </div>
                <span class="show-detail-btn">Lihat Detail</span>
            </div>

        @if($finalId)
            </a>
        @else
            </div>
        @endif
    @endforeach
</div>
</div>
{{-- ========================================================= --}}
{{-- SECTION: PALING BANYAK DITONTON (MOST WATCHED SERIES) --}}
{{-- ========================================================= --}}
<div class="section-header mt-5">
    <h2 class="section-title">
        <i class="fas fa-users"></i> Paling Banyak Ditonton
    </h2>
</div>

<div class="shows-grid">
    @forelse($mostWatchedShows as $show)
        @php
            // Membersihkan ID dan memastikan format tt + 7 digit angka
            $digits = preg_replace('/[^0-9]/', '', $show->tconst);
            $finalId = 'tt' . str_pad($digits, 7, '0', STR_PAD_LEFT);
        @endphp
        
        {{-- Area Klik: Bungkus seluruh kartu dengan tag <a> --}}
        <a href="{{ route('titles.show', $finalId) }}" class="show-card" style="text-decoration: none;">
            <div class="show-card-image">
                <img src="https://via.placeholder.com/300x450?text=Loading..." 
                     class="tmdb-poster" 
                     alt="{{ $show->primaryTitle }}"
                     data-id="{{ $finalId }}" 
                     data-type="tv">
            </div>
            
            <div class="show-card-content">
                <h3 class="show-title">{{ Str::limit($show->primaryTitle, 35) }}</h3>
                
                <div class="show-meta">
                    <span>
                        <i class="fas fa-eye text-pink me-1"></i> 
                        {{ number_format($show->numVotes / 1000, 1) }}K Penonton
                    </span>
                    <span style="color: white; font-weight: bold;">
                        <i class="fas fa-star text-pink me-1"></i> 
                        {{ number_format($show->averageRating, 1) }}
                    </span>
                </div>
                
                {{-- Tombol ini sekarang hanya sebagai visual --}}
                <div class="show-detail-btn">
                    Lihat Detail
                </div>
            </div>
        </a>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            Belum ada data serial populer yang ditemukan.
        </div>
    @endforelse
</div>

</div>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const apiKey = '8e8ed515442c24035b99b36d4bbb8e6d'; 

    $('.tmdb-poster').each(function() {
        const imgElement = $(this);
        const imdbId = imgElement.data('id');
        
        if (!imdbId) return;

        // Mencari data berdasarkan IMDb ID
        const url = `https://api.themoviedb.org/3/find/${imdbId}?api_key=${apiKey}&external_source=imdb_id`;

        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                let imagePath = null;
                
                // Prioritaskan hasil TV (untuk Drakor/Series)
                if (data.tv_results && data.tv_results.length > 0) {
                    imagePath = data.tv_results[0].poster_path;
                } else if (data.movie_results && data.movie_results.length > 0) {
                    imagePath = data.movie_results[0].poster_path;
                }

                if (imagePath) {
                    imgElement.attr('src', 'https://image.tmdb.org/t/p/w500' + imagePath);
                } else {
                    imgElement.attr('src', 'https://via.placeholder.com/300x450/1a1a1a/555555?text=No+Poster');
                }
            }
        });
    });
});
</script>
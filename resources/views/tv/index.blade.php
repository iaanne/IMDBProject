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
        height: 200px;
        background: linear-gradient(135deg, #2a2a2a, #1a1a1a);
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border-color);
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

    .show-card-image i {
        font-size: 3.5rem;
        color: rgba(255, 255, 255, 0.05);
        transition: 0.3s;
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
</style>

{{-- HERO SECTION --}}
<div class="hero-section">
    <div class="hero-content text-center container">
        <h1 class="hero-title">Dunia Serial TV</h1>
        <p class="hero-subtitle">Temukan serial TV terbaik dari berbagai genre dan jaringan.</p>
        
        {{-- FORM PENCARIAN ATAS --}}
        <form action="{{ route('search') }}" method="GET" class="search-form mx-auto" style="max-width: 600px;">
            <div class="search-input-group">
                <input type="text" name="q" class="search-input" placeholder="Cari judul serial, aktor, sutradara..." value="{{ request('q') }}">
                <button class="btn-custom" type="submit">
                    <i class="fas fa-search me-2"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container pb-5">

    {{-- JUDUL DINAMIS --}}
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-tv"></i>
            @if(request('network'))
                Hasil Filter: <span class="text-pink ms-2">{{ request('network') }}</span>
                <a href="{{ route('tv.index') }}" class="btn btn-sm btn-outline-light ms-3" style="border-radius: 20px; font-size: 0.8rem;">
                    <i class="fas fa-times me-1"></i> Reset
                </a>
            @else
                 Serial TV Populer
            @endif
        </h2>
    </div>
    
    {{-- GRID TV SHOWS --}}
    <div class="shows-grid">
        @forelse($topShows as $show)
            <div class="show-card">
                <div class="show-card-image">
                    {{-- Icon Placeholder --}}
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="show-card-content">
                    <h3 class="show-title">
                        {{ $show->name ?? $show->primaryTitle ?? 'Tanpa Judul' }}
                    </h3>
                    
                    <div class="show-meta">
                        <span>
                            <i class="fas fa-layer-group me-1 text-pink"></i>
                            {{ $show->number_of_seasons ?? '?' }} Season
                        </span>
                        <span style="color: white; font-weight: bold;">
                            <i class="fas fa-star me-1 text-pink"></i>
                            {{ isset($show->averageRating) ? number_format($show->averageRating, 1) : '-' }}
                        </span>
                    </div>
                    
                    {{-- SMART LINK: FIX 404 (FINAL FIX) --}}
                    @php
                        // Ambil ID Mentah
                        $rawId = $show->tconst ?? $show->show_id ?? null;
                        $finalId = null;

                        if ($rawId) {
                            // Ambil angka saja
                            $digits = preg_replace('/[^0-9]/', '', $rawId);
                            
                            // FORMAT AJAIB: 'tt' + Angka Nol (Padding 7 Digit)
                            // Contoh: 91630 -> tt0091630
                            if (!empty($digits)) {
                                $finalId = 'tt' . str_pad($digits, 7, '0', STR_PAD_LEFT);
                            }
                        }
                    @endphp

                    @if($finalId)
                        <a href="{{ route('titles.show', $finalId) }}" class="show-detail-btn">
                            Lihat Detail
                        </a>
                    @else
                        <button class="show-detail-btn" disabled style="opacity: 0.5; cursor: not-allowed; border-color: #555; color: #777;">
                            Unavailable
                        </button>
                    @endif

                </div>
            </div>
        @empty
            <div class="col-12" style="grid-column: 1 / -1;">
                <div class="text-center py-5">
                    <i class="fas fa-film fa-4x mb-4 text-muted"></i>
                    <h4 class="text-white mb-2">Tidak ada serial TV ditemukan</h4>
                    <p class="text-muted">Coba cari dengan kata kunci lain atau reset filter.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- BAGIAN JARINGAN --}}
    <div class="section-header mt-5">
        <h2 class="section-title"><i class="fas fa-broadcast-tower"></i> Jaringan Populer</h2>
        <span class="badge bg-dark border border-secondary text-white rounded-pill px-3">Top 12</span>
    </div>
    
    <div class="networks-grid">
        @foreach(array_slice($networks, 0, 12) as $network)
            <a href="{{ route('tv.index', ['network' => $network->name]) }}" 
               class="network-card" 
               title="{{ $network->name }}">
               <div class="network-icon"><i class="fas fa-satellite-dish"></i></div>
               <div class="network-name">{{ $network->name }}</div>
            </a>
        @endforeach
    </div>

    {{-- SEARCH BAR BAWAH --}}
    <div class="network-search-section">
        <h3 class="text-white mb-2" style="font-weight: 700;">Tidak menemukan jaringan favoritmu?</h3>
        <p class="text-white mb-4 opacity-75">Cari dari database lengkap kami yang mencakup ratusan jaringan global.</p>
        
        <form action="{{ route('tv.index') }}" method="GET">
            <div class="input-group" style="max-width: 500px; margin: 0 auto;">
                <input type="text" name="network" class="network-search-input" placeholder="Ketik nama jaringan (misal: HBO, BBC)..." required>
                <button class="btn-custom" style="border-radius: 0 50px 50px 0;" type="submit">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
            </div>
        </form>
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
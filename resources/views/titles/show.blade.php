@extends('layouts.app')

{{-- Judul Browser: Cek primaryTitle atau name --}}
@section('title', ($detail->primaryTitle ?? $detail->name ?? 'Detail') . ' - Showfy')

@section('content')
<style>
    /* === 1. DEFINISI WARNA (Supaya Tampilannya Konsisten) === */
    :root {
        --c-rose: #d95f8c;       /* Pink Utama */
        --c-amaranth: #870339;   /* Merah Gelap */
        --c-dark: #0d0d0d;       /* Hitam Background */
        --c-card: #141414;       /* Hitam Card */
        --c-border: rgba(255, 255, 255, 0.1);
    }

    /* === 2. DETAIL PAGE STYLES === */
    
    /* Hero Section dengan Overlay Gradient */
    .detail-hero {
        position: relative;
        background: linear-gradient(to right, #0d0d0d 10%, rgba(13, 13, 13, 0.95) 40%, rgba(13, 13, 13, 0.6) 100%),
                    url('https://source.unsplash.com/random/1920x1080/?cinema,dark,movie');
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        margin-bottom: 40px;
        border-bottom: 1px solid var(--c-border);
    }

    /* Poster Placeholder */
    .poster-placeholder {
        width: 300px;
        height: 450px;
        background: linear-gradient(135deg, #1a1a1a, #050505);
        border: 1px solid var(--c-border);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 50px rgba(217, 95, 140, 0.15); /* Glow tipis pink */
        transition: transform 0.3s ease;
    }
    .poster-placeholder:hover {
        transform: scale(1.02);
    }
    .poster-placeholder i {
        font-size: 5rem;
        color: rgba(255, 255, 255, 0.1);
    }

    /* Typography Judul (Gradient Text) */
    .detail-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 15px;
        line-height: 1.1;
        background: linear-gradient(90deg, #ffffff, var(--c-rose));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Metadata Info */
    .detail-meta {
        font-size: 1.1rem;
        color: #e0e0e0;
        display: flex;
        gap: 20px;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    .meta-item i {
        color: var(--c-rose);
        margin-right: 8px;
    }

    /* ID Badge */
    .id-badge {
        background: rgba(217, 95, 140, 0.1); /* Pink transparan */
        color: var(--c-rose);
        border: 1px solid rgba(217, 95, 140, 0.3);
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Rating Box */
    .rating-box {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        background: rgba(255, 255, 255, 0.05);
        padding: 12px 25px;
        border-radius: 50px;
        border: 1px solid var(--c-border);
        margin-bottom: 30px;
        transition: 0.3s;
    }
    .rating-box:hover {
        border-color: var(--c-rose);
        background: rgba(217, 95, 140, 0.05);
    }
    .rating-score {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fbbf24; /* Gold */
    }

    /* Genre Badges */
    .genre-badge {
        background: linear-gradient(90deg, var(--c-amaranth), var(--c-rose));
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-right: 8px;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
        border: none;
    }
    .genre-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(217, 95, 140, 0.4);
        color: white;
    }

    /* Cast & Crew Grid */
    .people-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 50px;
    }
    .person-card {
        background: var(--c-card);
        border: 1px solid var(--c-border);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .person-card:hover {
        border-color: var(--c-rose);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(217, 95, 140, 0.1);
    }
    .person-avatar {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #666;
    }
    .person-name {
        font-weight: 700;
        color: white;
        margin-bottom: 5px;
        font-size: 1rem;
    }
    .person-role {
        font-size: 0.85rem;
        color: #a3a3a3;
    }

    /* Role Badge (Actor/Actress) */
    .role-badge {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: transparent;
        border: 1px solid var(--c-rose);
        color: var(--c-rose);
        padding: 3px 10px;
        border-radius: 50px;
        margin-top: 8px;
        display: inline-block;
    }

    .section-header-small {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        border-left: 4px solid var(--c-rose);
        padding-left: 15px;
        color: white;
    }

    /* Button Gradient Custom */
    .btn-gradient {
        background: linear-gradient(135deg, var(--c-amaranth), var(--c-rose));
        color: white;
        border: none;
    }
    .btn-gradient:hover {
        background: linear-gradient(135deg, var(--c-rose), var(--c-amaranth));
        color: white;
        box-shadow: 0 5px 15px rgba(217, 95, 140, 0.3);
    }

    @media (max-width: 768px) {
        .detail-hero { text-align: center; }
        .hero-content { flex-direction: column; align-items: center; }
        .poster-placeholder { margin-bottom: 30px; width: 220px; height: 330px; }
        .detail-title { font-size: 2.5rem; }
        .detail-meta { justify-content: center; }
    }
</style>

{{-- 1. HERO SECTION --}}
<div class="detail-hero">
    <div class="container">
        <div class="d-flex hero-content gap-5 align-items-start">
            
            {{-- Kiri: Poster --}}
            <div class="poster-placeholder flex-shrink-0">
                @if(isset($detail->titleType) && ($detail->titleType == 'movie'))
                    <i class="fas fa-film"></i>
                @else
                    <i class="fas fa-tv"></i>
                @endif
            </div>

            {{-- Kanan: Informasi --}}
            <div class="info-content flex-grow-1">
                {{-- JUDUL: Cek primaryTitle ATAU name (untuk TV Show) --}}
                <h1 class="detail-title">
                    {{ $detail->primaryTitle ?? $detail->name ?? 'Judul Tidak Tersedia' }}
                </h1>

                <div class="detail-meta">
                    <span class="meta-item">
                        <i class="fas fa-video"></i> 
                        {{ isset($detail->titleType) ? ucfirst($detail->titleType) : 'TV Show/Movie' }}
                    </span>
                    
                    {{-- TAHUN: Support Range untuk TV Series --}}
                    <span class="meta-item">
                        <i class="fas fa-calendar-alt"></i> 
                        @if(isset($detail->endYear) && $detail->endYear)
                            {{ $detail->startYear }} - {{ $detail->endYear }}
                        @else
                            {{ $detail->startYear ?? 'N/A' }}
                        @endif
                    </span>

                    <span class="meta-item">
                        <i class="fas fa-clock"></i> 
                        {{ (isset($detail->runtimeMinutes) && $detail->runtimeMinutes) ? $detail->runtimeMinutes . ' menit' : 'Episodic' }}
                    </span>
                    
                    {{-- ID Badge --}}
                    <span class="id-badge">
                        ID: {{ $detail->tconst ?? $detail->show_id ?? 'N/A' }}
                    </span>
                </div>

                {{-- Genre --}}
                <div class="mb-4">
                    @forelse ($genres as $g)
                        <span class="genre-badge">{{ $g->genre_name }}</span>
                    @empty
                        <span class="text-white opacity-50 fst-italic">Genre tidak tersedia</span>
                    @endforelse
                </div>

                {{-- Rating Box --}}
                <div class="rating-box">
                    @if ($rating)
                        <i class="fas fa-star text-warning fa-2x"></i>
                        <div>
                            {{-- Cek nama kolom rating (averageRating atau vote_average) --}}
                            <div class="rating-score">
                                {{ number_format($rating->averageRating ?? $rating->vote_average ?? 0, 1) }}
                                <span class="fs-6 text-white opacity-50 fw-normal">/10</span>
                            </div>
                            <div class="small text-white opacity-75">
                                {{ number_format($rating->numVotes ?? $rating->vote_count ?? 0) }} votes
                            </div>
                        </div>
                    @else
                        <i class="far fa-star fa-2x text-white opacity-25"></i>
                        <span class="text-white opacity-75 fs-5">Belum ada rating</span>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-3 justify-content-center justify-content-md-start">
                    
                    {{-- Tombol Watchlist --}}
                    @auth
                        {{-- Ambil ID yang tersedia --}}
                        @php $contentId = $detail->tconst ?? $detail->show_id ?? null; @endphp

                        @if($contentId)
                            <button onclick="toggleWatchlist('{{ $contentId }}')" 
                                    id="watchlistBtn"
                                    class="btn {{ $isInWatchlist ? 'btn-gradient border-0 text-white' : 'btn-outline-light' }} px-4 py-2 rounded-pill fw-bold transition-all"
                                    style="border-width: 2px;">
                                
                                <i class="fas {{ $isInWatchlist ? 'fa-check' : 'fa-plus' }} me-2" id="watchlistIcon"></i> 
                                <span id="watchlistText">{{ $isInWatchlist ? 'Added to Watchlist' : 'Add to Watchlist' }}</span>
                            </button>
                        @endif
                    @else
                        <button class="btn btn-outline-light px-4 py-2 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal" style="border-width: 2px;">
                            <i class="fas fa-plus me-2"></i> Add to Watchlist
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">

    {{-- 2. CAST SECTION --}}
    <h3 class="section-header-small">🎬 Pemeran Utama</h3>

    @if (count($cast) > 0)
        <div class="people-grid">
            @foreach ($cast as $c)
                <div class="person-card">
                    <div class="person-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="person-name">{{ $c->PersonName ?? 'Unknown' }}</div>
                    
                    @if (isset($c->characters))
                        <div class="person-role">as {{ str_replace(['[', ']', '"'], '', $c->characters) }}</div>
                    @endif
                    
                    <span class="role-badge">{{ $c->Category ?? 'Cast' }}</span>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-dark text-center text-white opacity-75 border border-secondary">
            <i class="fas fa-user-slash mb-2"></i><br>
            Tidak ada data pemeran untuk judul ini.
        </div>
    @endif

    <hr class="border-secondary my-5 opacity-25">

    {{-- 3. CREW SECTION --}}
    <h3 class="section-header-small">🎥 Kru Produksi</h3>

    @if (isset($crew) && count($crew) > 0)
        <div class="people-grid">
            @foreach ($crew as $c)
                <div class="person-card">
                    <div class="person-avatar">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="person-name">{{ $c->PersonName ?? 'Unknown' }}</div>
                    <span class="role-badge border border-secondary text-white" style="border-color: #666; color: #aaa;">{{ ucfirst($c->Category ?? 'Crew') }}</span>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-white opacity-50">Tidak ada data kru.</p>
    @endif

    {{-- Tombol Kembali --}}
    <div class="mt-5">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4 rounded-pill">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function toggleWatchlist(tconst) {
        const btn = document.getElementById('watchlistBtn');
        const icon = document.getElementById('watchlistIcon');
        const text = document.getElementById('watchlistText');

        btn.style.opacity = '0.7';
        
        fetch("{{ route('watchlist.toggle') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ tconst: tconst })
        })
        .then(response => response.json())
        .then(data => {
            btn.style.opacity = '1';

            if (data.status === 'added') {
                btn.classList.remove('btn-outline-light');
                btn.classList.add('btn-gradient', 'border-0', 'text-white');
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-check');
                text.innerText = 'Added to Watchlist';
            } else {
                btn.classList.remove('btn-gradient', 'border-0', 'text-white');
                btn.classList.add('btn-outline-light');
                icon.classList.remove('fa-check');
                icon.classList.add('fa-plus');
                text.innerText = 'Add to Watchlist';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.style.opacity = '1';
        });
    }
</script>
@endsection
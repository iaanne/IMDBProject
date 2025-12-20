@extends('layouts.app')

@section('title', 'Beranda TV Show - Temukan Serial Terbaik')

@section('content')
<style>
    body {
        background-color: #0f172a;
        color: white;
    }
    .hero-section {
        background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('https://source.unsplash.com/random/1600x900/?television') no-repeat center center;
        background-size: cover;
        padding: 100px 0;
        text-align: center;
        border-radius: 0 0 30px 30px;
    }
    .search-form {
        max-width: 600px;
        margin: 0 auto;
    }
    .search-input {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }
    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    .section-title {
        border-left: 5px solid #f97316; /* Warna oranye */
        padding-left: 15px;
        margin-bottom: 25px;
        font-weight: bold;
    }
    .show-card {
        background-color: #1e293b;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        height: 100%; /* Agar tinggi kartu sama rata */
        border: 1px solid #334155;
    }
    .show-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        border-color: #f97316;
    }
    .network-badge {
        background-color: #334155;
        color: #e2e8f0;
        padding: 8px 16px;
        border-radius: 20px;
        text-decoration: none;
        display: inline-block;
        margin: 5px;
        transition: background-color 0.2s;
    }
    .network-badge:hover {
        background-color: #f97316; /* Ubah jadi oranye saat hover */
        color: white;
    }
</style>

{{-- HERO SECTION --}}
<div class="hero-section">
    <div class="container">
        <h1 class="display-4 fw-bold">Dunia Serial TV</h1>
        <p class="lead">Temukan serial TV terbaik dari berbagai genre dan jaringan.</p>
        
        {{-- FORM PENCARIAN --}}
        <form action="{{ route('search') }}" method="GET" class="search-form mt-4">
            <div class="input-group input-group-lg">
                <input type="text" name="q" class="form-control search-input" placeholder="Cari judul serial, aktor, sutradara..." value="{{ request('q') }}">
                <button class="btn btn-warning" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container mt-5">

    {{-- DAFTAR SERIAL TV TERPOPULER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title h3 mb-0">🔥 Serial TV Populer</h2>
    </div>

    {{-- GRID SYSTEM --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($topShows as $show) 
            <div class="col">
                <div class="show-card">
                    <div class="card-body p-4 d-flex flex-column h-100">
                        {{-- Bagian Atas: Ikon & Judul --}}
                        <div class="mb-auto">
                            <div class="mb-3 text-warning">
                                <i class="fas fa-tv fa-2x"></i> </div>
                            
                            <h5 class="card-title text-white fw-bold mb-3">
                                {{ $show->name ?? $show->primaryTitle ?? 'Tanpa Judul' }}
                            </h5>

                            <div class="d-flex justify-content-between align-items-center mb-3 text-secondary">
                                <span>
                                    <i class="fas fa-layer-group me-1"></i> 
                                    {{ $show->number_of_seasons ?? '?' }} Season
                                </span>
                                <span class="text-warning fw-bold">
                                    <i class="fas fa-star me-1"></i> 
                                    {{ isset($show->averageRating) ? number_format($show->averageRating, 1) : '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Detail (Akan selalu di bawah) --}}
                        <a href="#" class="btn btn-outline-warning w-100 mt-3">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-dark text-center py-5">
                    <i class="fas fa-film fa-3x mb-3 text-muted"></i>
                    <p class="mb-0">Belum ada data serial TV yang ditampilkan.</p>
                </div>
            </div>
        @endforelse
    </div>

    <hr class="my-5 border-secondary">

    {{-- JELAJAHI BERDASARKAN JARINGAN TV --}}
    <h2 class="section-title h3">📡 Jelajahi Berdasarkan Jaringan</h2>
    <div class="text-center py-4">
        @if(isset($networks))
            @forelse ($networks as $network)
                <a href="#" class="network-badge">
                    {{ $network->name }}
                </a>
            @empty
                <p class="text-muted">Belum ada data jaringan TV.</p>
            @endforelse
        @endif
    </div>

</div>
@endsection
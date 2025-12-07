{{-- resources/views/tv/index.blade.php --}}

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
        border-left: 5px solid #f97316; -- Warna oranye untuk membedakan dari film
        padding-left: 15px;
        margin-bottom: 25px;
    }
    .show-card {
        background-color: #1e293b;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        height: 100%;
    }
    .show-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
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
        background-color: #475569;
        color: white;
    }
</style>

{{-- HERO SECTION --}}
<div class="hero-section">
    <div class="container">
        <h1 class="display-4 fw-bold">Dunia Serial TV</h1>
        <p class="lead">Temukan serial TV terbaik dari berbagai genre dan jaringan.</p>
        
        {{-- FORM PENCARIAN --}}
        <form action="{{ route('titles.search') }}" method="GET" class="search-form mt-4">
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
    <h2 class="section-title h3">📺 Serial TV Terpopuler</h2>
    <div class="row">
        @forelse ($topShows as $show)
            <div class="col-md-3 col-sm-6 mb-4">
                <a href="#" class="text-decoration-none text-white"> {{-- Ganti nanti dengan route detail show --}}
                    <div class="show-card">
                        <img src="https://via.placeholder.com/300x450.png?text={{ $show->name }}" class="card-img-top" alt="{{ $show->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($show->name, 25) }}</h5>
                            <p class="card-text text-secondary">
                                {{ $show->number_of_seasons ?? 'N/A' }} Musim • {{ $show->vote_average ? number_format($show->vote_average, 1) . ' ⭐' : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p>Belum ada data serial TV terpopuler.</p>
        @endforelse
    </div>

    <hr class="my-5 border-secondary">

    {{-- JEJLAHI BERDASARKAN JARINGAN TV --}}
    <h2 class="section-title h3">📡 Jelajahi Berdasarkan Jaringan</h2>
    <div class="text-center">
        @forelse ($networks as $network)
            <a href="#" class="network-badge"> {{-- Ganti nanti dengan route untuk jaringan --}}
                {{ $network->network_name }}
            </a>
        @empty
            <p>Belum ada data jaringan TV.</p>
        @endforelse
    </div>

</div>

@endsection
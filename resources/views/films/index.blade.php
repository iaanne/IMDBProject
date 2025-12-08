{{-- resources/views/films/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Beranda - Jelajahi Film & TV Show')

@section('content')
    <style>
        body {
            background-color: #0f172a;
            color: white;
        }

        .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('https://source.unsplash.com/random/1600x900/?movies') no-repeat center center;
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
            border-left: 5px solid #38bdf8;
            padding-left: 15px;
            margin-bottom: 25px;
        }

        .film-card {
            background-color: #1e293b;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .film-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .genre-badge {
            background-color: #334155;
            color: #e2e8f0;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: background-color 0.2s;
        }

        .genre-badge:hover {
            background-color: #475569;
            color: white;
        }
    </style>

    {{-- HERO SECTION --}}
    <div class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Jelajahi Dunia Film</h1>
            <p class="lead">Temukan film, serial TV, aktor, dan sutradara favorit Anda.</p>

            {{-- FORM PENCARIAN --}}
            <form action="{{ route('search') }}" method="GET" class="search-form mt-4">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control search-input"
                        placeholder="Cari judul film, aktor, sutradara..." value="{{ request('q') }}">
                    <button class="btn btn-warning" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="container mt-5">

        {{-- DAFTAR FILM TERPOPULER --}}
        <h2 class="section-title h3">🔥 Film Terpopuler</h2>
        <div class="row">
            @forelse ($topFilms as $film)
                <div class="col-md-3 col-sm-6 mb-4">
                    <a href="{{ route('titles.show', $film->tconst) }}" class="text-decoration-none text-white">
                        <div class="film-card">
                            <img src="https://via.placeholder.com/300x450.png?text={{ $film->primaryTitle }}"
                                class="card-img-top" alt="{{ $film->primaryTitle }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($film->primaryTitle, 25) }}</h5>
                                <p class="card-text text-secondary">
                                    {{ $film->startYear ?? 'N/A' }} •
                                    {{ $film->averageRating ? number_format($film->averageRating, 1) . ' ⭐' : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p>Belum ada data film terpopuler.</p>
            @endforelse
        </div>

        <hr class="my-5 border-secondary">

        {{-- JEJLAHI BERDASARKAN GENRE --}}
        <h2 class="section-title h3">🎭 Jelajahi Berdasarkan Genre</h2>
        <div class="text-center">
            @forelse ($genres as $genre)
                <a href="{{ route('search') }}?q={{ urlencode($genre->genre_name) }}" class="genre-badge">
                    {{ $genre->genre_name }}
                </a>
            @empty
                <p>Belum ada data genre.</p>
            @endforelse
        </div>

    </div>

@endsection



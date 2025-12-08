@extends('layouts.app')

@section('title', 'Showfy - Temukan Film & Serial TV Favorit')

@section('content')
    <div class="container-fluid p-0">

        {{-- 1. HERO PREVIEW SECTION --}}
        @if ($featuredMovie)
            <div class="hero-preview"
                style="background-image: url('https://source.unsplash.com/random/1600x900/?movie,{{ $featuredMovie->primaryTitle }}');">
                <div class="container">
                    <h1 class="display-4 fw-bold">{{ $featuredMovie->primaryTitle }}</h1>
                    <p class="lead">
                        Tahun {{ $featuredMovie->startYear ?? 'N/A' }} • {{ $featuredMovie->runtimeMinutes ?? 'N/A' }} menit
                        •
                        <i class="fas fa-star"></i> {{ number_format($featuredMovie->averageRating, 1) }}
                        ({{ number_format($featuredMovie->numVotes) }} votes)
                    </p>
                    <a href="{{ route('titles.show', $featuredMovie->tconst) }}" class="btn-details">
                        <i class="fas fa-play-circle me-2"></i> Lihat Detail
                    </a>
                </div>
            </div>
        @endif

        <div class="container">

            {{-- 2. DAFTAR TOP 10 MOVIES --}}
            {{-- ... di dalam file home.blade.php ... --}}

            {{-- DAFTAR TOP 10 MOVIES (MENGGUNAKAN GRID) --}}
            <div class="movie-section">
                <h2 class="movie-row-title">🔥 Top 10 Movies</h2>

                <div class="card-grid">
                    @forelse ($topMovies as $movie)
                        <a href="{{ route('titles.show', $movie->tconst) }}" class="grid-card">
                            <h5 class="card-title">{{ Str::limit($movie->primaryTitle, 30) }}</h5>
                            <p class="card-meta">
                                Tahun {{ $movie->startYear ?? 'N/A' }}
                            </p>
                            <p class="rating">
                                <i class="fas fa-star"></i> {{ number_format($movie->averageRating, 1) }}
                            </p>
                        </a>
                    @empty
                        <p class="text-center w-100">Belum ada film populer.</p>
                    @endforelse
                </div>
            </div>

            {{-- DAFTAR REKOMENDASI (MENGGUNAKAN GRID) --}}
            <div class="movie-section">
                <h2 class="movie-row-title">✨ Direkomendasikan Untuk Anda</h2>

                <div class="card-grid">
                    @forelse ($recommendedMovies as $movie)
                        <a href="{{ route('titles.show', $movie->tconst) }}" class="grid-card">
                            <h5 class="card-title">{{ Str::limit($movie->primaryTitle, 30) }}</h5>
                            <p class="card-meta">
                                Tahun {{ $movie->startYear ?? 'N/A' }}
                            </p>
                            <p class="rating">
                                <i class="fas fa-star"></i> {{ number_format($movie->averageRating, 1) }}
                            </p>
                        </a>
                    @empty
                        <p class="text-center w-100">Belum ada rekomendasi.</p>
                    @endforelse
                </div>
            </div>

    {{-- ... di akiran file home.blade.php ... --}}

    {{-- HAPUS SCRIPT LAMA ANDA --}}

    {{-- GANTI DENGAN INI --}}
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function setupArrows(rowId) {
                    const container = document.querySelector(`#${rowId}`).closest('.movie-row-container');
                    const row = document.getElementById(rowId);
                    const prevBtn = container.querySelector('.prev');
                    const nextBtn = container.querySelector('.next');

                    if (row) {
                        prevBtn.addEventListener('click', () => {
                            row.scrollBy({
                                left: -220,
                                behavior: 'smooth'
                            });
                        });
                        nextBtn.addEventListener('click', () => {
                            row.scrollBy({
                                left: 220,
                                behavior: 'smooth'
                            });
                        });
                    }
                }

                setupArrows('top-movies-row');
                setupArrows('recommended-movies-row');
            });
        </script>
    @endsection

@extends('layouts.app')

@section('title', 'Popular Movies')

@section('content')

<div class="container py-5 text-white">

    <h1 class="section-title text-center mb-4">🔥 Popular Movies</h1>

    {{-- Jika terjadi error --}}
    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    <div class="row">

        @foreach($popular as $movie)
        <div class="col-md-3 col-sm-6 mb-4">

            <div class="scenepix-card" 
                 onclick="window.location.href='{{ route('titles.show', $movie->tconst) }}'">

                <div class="card-body">

                    {{-- TITLE --}}
                    <h5 class="movie-title">
                        {{ Str::limit($movie->primaryTitle, 35) }}
                    </h5>

                    {{-- YEAR --}}
                    <p class="movie-meta">
                        {{ $movie->startYear ?? 'Unknown' }}
                    </p>

                    {{-- RATING --}}
                    @if(isset($movie->popularityScore))
                        <p class="movie-runtime text-warning">
                            ⭐ Popularity: {{ number_format($movie->popularityScore, 1) }}
                        </p>
                    @endif

                </div>
            </div>

        </div>
        @endforeach

    </div>

</div>

@endsection

@section('styles')
<style>
    .section-title {
        color: #FF8F8F;
        font-weight: 800;
    }
    .scenepix-card {
        background: rgba(255,255,255,0.05);
        border-radius: 14px;
        padding: 18px;
        border: 1px solid rgba(255,255,255,0.1);
        cursor: pointer;
        transition: 0.3s;
        height: 160px;
    }
    .scenepix-card:hover {
        transform: translateY(-7px);
        border-color: #FF8F8F;
        box-shadow: 0 12px 25px rgba(0,0,0,0.4);
    }
    .movie-title {
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
    }
    .movie-meta {
        color: #8CE4FF;
        font-size: .9rem;
    }
</style>
@endsection

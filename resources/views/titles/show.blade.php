@extends('layouts.app')

@section('title', $detail->primaryTitle)

@section('content')

<style>
    body {
        background-color: #0f172a;
        color: white;
    }
    .movie-hero {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }
    .rating-box {
        background: #1e293b;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
    }
    .genre-badge {
        background: #38bdf8;
        color: #0f172a;
        padding: 6px 12px;
        border-radius: 15px;
        margin-right: 6px;
        font-weight: 600;
    }
    .cast-card {
        background: #1e293b;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: 0.2s;
    }
    .cast-card:hover {
        transform: translateX(5px);
        background: #334155;
    }
</style>


<div class="container mt-4">

    {{-- HERO / TITLE --}}
    <div class="movie-hero">
        <h1 class="fw-bold">{{ $detail->primaryTitle }}</h1>

        <p class="text-pink mt-2">
            {{ ucfirst($detail->titleType) }} • 
            {{ $detail->startYear ?? 'N/A' }} • 
            {{ $detail->runtimeMinutes ? $detail->runtimeMinutes . ' menit' : 'Durasi tidak tersedia' }}
        </p>

        <div class="mt-3">
            <span class="badge bg-warning text-dark">ID: {{ $detail->tconst }}</span>
        </div>
    </div>


    {{-- RATING --}}
    <div class="rating-box text-center">
        @if($rating)
            <h2 class="text-warning">⭐ {{ $rating->averageRating }} / 10</h2>
            <p class="text-secondary">{{ number_format($rating->numVotes) }} votes</p>
        @else
            <p class="text-muted">Belum ada rating.</p>
        @endif
    </div>


    {{-- GENRE --}}
    <h3 class="mb-3">🎭 Genre</h3>

    @if(count($genres) > 0)
        @foreach($genres as $g)
            <span class="genre-badge">{{ $g->genre_name }}</span>
        @endforeach
    @else
        <p class="text-muted">Genre tidak tersedia.</p>
    @endif

    <hr class="border-secondary my-4">

    {{-- CAST --}}
    <h3 class="mb-3">🎬 Pemeran & Kru</h3>

    @if(count($cast) > 0)
        @foreach($cast as $c)
        <div class="cast-card">
            <strong>{{ $c->PersonName }}</strong>  
            <br>
            <span class="text-info">{{ $c->Category }}</span>

            @if($c->characters)
                <br>
                <small class="text-secondary">Sebagai: {{ $c->characters }}</small>
            @endif
        </div>
        @endforeach
    @else
        <p class="text-muted">Tidak ada data cast untuk title ini.</p>
    @endif

</div>

@endsection

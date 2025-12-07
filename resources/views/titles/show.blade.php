@extends('layouts.app')

@section('title', $detail->primaryTitle)

@section('content')


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
            @if ($rating)
                <h2 class="text-warning">
            ⭐ {{ number_format($rating->averageRating, 2) }} / 10
                </h2>
                <p class="text-secondary"> {{ $rating->averageRating }} votes</p>
            @else
                <p class="text-muted">Belum ada rating.</p>
            @endif
        </div>

        


        {{-- GENRE --}}
        <h3 class="mb-3">🎭 Genre</h3>

        @if (count($genres) > 0)
            @foreach ($genres as $g)
                <span class="genre-badge">{{ $g->genre_name }}</span>
            @endforeach
        @else
            <p class="text-muted">Genre tidak tersedia.</p>
        @endif

        <hr class="border-secondary my-4">
        {{-- CAST --}}
        <h3 class="mb-3">🎬 Pemeran Utama</h3>

        @if (count($cast) > 0)
            <div class="cast-grid">
                @foreach ($cast as $c)
                <div class="cast-card">
                    <strong>{{ $c->PersonName }}</strong><br>
                    <span class="text-info">{{ $c->Category }}</span>

                    @if ($c->characters)
                     <br><small class="text-secondary">Sebagai: {{ $c->characters }}</small>
                    @endif
            </div>
                 @endforeach
        </div>
@else
    <p class="text-muted">Tidak ada data pemeran.</p>
@endif


        <hr class="border-secondary my-4">

        {{-- CREW --}}
        <h3 class="mb-3">👨‍💼 Kru (Sutradara, Penulis, dll.)</h3>

@           @if (isset($crew) && count($crew) > 0)
            <div class="cast-grid">
                 @foreach ($crew as $c)
                 <div class="cast-card">
                    <strong>{{ $c->PersonName }}</strong><br>
                    <span class="text-info">{{ $c->Category }}</span>
                 </div>
                @endforeach
            </div>
            @else
             <p class="text-muted">Tidak ada data kru.</p>
@endif

    </div>

@endsection

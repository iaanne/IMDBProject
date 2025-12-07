@extends('layouts.app')

@section('content')

<div class="home-wrapper">

    <h1 class="section-title">TOP 10 IN THIS WEEK</h1>

    <div class="top10-carousel-container">

        <!-- LEFT BUTTON -->
        <button class="carousel-btn left" id="btnLeft">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- TRACK -->
        <div class="carousel-track" id="top10Track">
            @foreach($top10 as $movie)
                <div class="movie-card">
                    <div class="movie-card-content">
                        <h3 class="movie-title">{{ $movie->primaryTitle }}</h3>
                        <div class="movie-rating">⭐ {{ number_format($movie->averageRating, 1) }}</div>
                        <div class="movie-votes">Votes: {{ number_format($movie->numVotes) }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- RIGHT BUTTON -->
        <button class="carousel-btn right" id="btnRight">
            <i class="fas fa-chevron-right"></i>
        </button>

    </div>

    {{-- ================= POPULAR MOVIES ================= --}}
    @if(isset($popular) && count($popular) > 0)
    <h1 class="section-title mt-5">🔥 Popular Movies</h1>

    <div class="top10-container">
    @foreach($popular as $movie)
        <div class="movie-card" onclick="window.location='/title/{{ $movie->tconst }}'">

            <div class="movie-icon-container">
                <i class="fas fa-fire movie-icon"></i>
            </div>

            <div class="movie-info">
                <h3 class="movie-title">{{ $movie->primaryTitle }}</h3>

                <p class="movie-year">
                    {{ $movie->startYear ?? 'N/A' }} • Popularity: {{ $movie->popularity ?? '-' }}
                </p>

                <div class="movie-rating">⭐
                    <span class="rating-value">
                        {{ $movie->averageRating ? number_format($movie->averageRating, 2) : '-' }}
                    </span>

                </div>
            </div>
        </div>
    @endforeach
    </div>
    @endif


    {{-- ================= MOVIES BY YEAR ================= --}}
    @if(isset($seasonal) && count($seasonal) > 0)
    <h1 class="section-title mt-5">📅 Movies Released in 2024</h1>

    <div class="top10-container">
        @foreach($seasonal as $movie)
        <div class="movie-card" onclick="window.location='/title/{{ $movie->tconst }}'">

            <div class="movie-icon-container">
                <i class="fas fa-calendar movie-icon"></i>
            </div>

            <div class="movie-info">
                <h3 class="movie-title">{{ $movie->primaryTitle }}</h3>

                <p class="movie-year">
                    {{ $movie->startYear ?? 'N/A' }}
                </p>

                <div class="movie-rating">  ⭐
                    <span class="rating-value">{{ $movie->averageRating ?? '-' }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif


</div>

@endsection


{{-- ############ JAVASCRIPT SLIDER ############ --}}
@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const track = document.getElementById("top10Track");
    const btnLeft = document.getElementById("btnLeft");
    const btnRight = document.getElementById("btnRight");

    // Width scroll per-click (satu card)
    const scrollAmount = 300;

    btnLeft.addEventListener("click", () => {
        track.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });

    btnRight.addEventListener("click", () => {
        track.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });
});
</script>
@endsection

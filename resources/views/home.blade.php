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

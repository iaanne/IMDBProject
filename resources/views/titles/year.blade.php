@extends('layouts.app')

@section('title', 'Movies by Year')

@section('content')

<div class="container py-5 text-white">

    <h1 class="section-title text-center mb-4">
        🎬 Movies Released in <span style="color:#8CE4FF">{{ $year }}</span>
    </h1>

    {{-- Form pilih tahun --}}
    <form method="GET" action="{{ route('titles.byYear') }}" class="mb-4 text-center">
        <input type="number" name="year" placeholder="Masukkan Tahun..." 
               class="form-control w-25 d-inline" min="1800" max="2100">
        <button class="btn btn-primary">Filter</button>
    </form>

    {{-- Error Handling --}}
    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    {{-- Jika tidak ada hasil --}}
    @if(isset($movies) && count($movies) === 0)
        <div class="text-center mt-5">
            <h3 class="text-muted">No movies found for this year.</h3>
        </div>
    @endif

    {{-- List Film --}}
    <div class="row">
        @foreach($movies as $movie)
        <div class="col-md-3 col-sm-6 mb-4">

            <div class="scenepix-card" 
                 onclick="window.location.href='{{ route('titles.show', $movie->tconst) }}'">

                <div class="card-body">

                    <h5 class="movie-title">
                        {{ Str::limit($movie->primaryTitle, 35) }}
                    </h5>

                    <p class="movie-meta">Runtime: {{ $movie->runtimeMinutes ?? 'N/A' }} min</p>

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
        height: 150px;
    }
    .scenepix-card:hover {
        transform: translateY(-7px);
        border-color: #38BDF8;
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

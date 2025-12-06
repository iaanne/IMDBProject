@extends('layouts.app')

@section('title', 'Search Results')

@section('content')

<div class="search-page">

    @if($keyword)
        <h1 class="section-title">
            Search Results for: <span style="color:#FF8F8F">{{ $keyword }}</span>
        </h1>
    @endif

    {{-- Jika ada error --}}
    @if(isset($error))
        <div class="alert alert-danger mt-3">
            {{ $error }}
        </div>
    @endif


    {{-- Jika tidak ada hasil --}}
    @if(isset($results) && count($results) === 0)
        <div class="no-results text-center mt-5">
            <i class="fas fa-search fa-4x mb-3" style="color:#8CE4FF"></i>
            <h3 class="text-light">No results found</h3>
            <p class="text-muted">Try a different keyword</p>
        </div>
    @endif


    {{-- Hasil pencarian --}}
    @if(isset($results) && count($results) > 0)

        <p class="text-light mb-4">
            <strong>{{ count($results) }}</strong> results found
        </p>

        <div class="row">

            @foreach($results as $title)
            <div class="col-md-3 col-sm-6 mb-4">
                
                <div class="scenepix-card" onclick="window.location.href='{{ route('titles.show',$title->tconst) }}'">

                    <div class="card-body">

                        {{-- TITLE --}}
                        <h5 class="movie-title">
                            {{ Str::limit($title->primaryTitle, 40) }}
                        </h5>

                        {{-- YEAR & TYPE --}}
                        <p class="movie-meta">
                            {{ $title->startYear ?? 'Unknown' }} • {{ ucfirst($title->titleType) }}
                        </p>

                        {{-- RUNTIME --}}
                        @if($title->runtimeMinutes)
                        <p class="movie-runtime">
                            <i class="fas fa-clock me-1"></i>
                            {{ $title->runtimeMinutes }} min
                        </p>
                        @endif

                    </div>

                </div>

            </div>
            @endforeach
            
        </div>

    @endif

</div>

@endsection


@section('styles')
<style>
    .movie-title {
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .movie-meta {
        color: #8CE4FF;
        font-size: 0.9rem;
    }

    .movie-runtime {
        color: #FF8F8F;
        font-size: 0.85rem;
    }

    .scenepix-card {
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 15px;
        transition: 0.3s;
        border: 1px solid rgba(255,255,255,0.1);
        cursor: pointer;
    }

    .scenepix-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.3);
        border-color: #FF8F8F;
    }
</style>
@endsection

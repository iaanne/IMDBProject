@extends('layouts.app')

@section('title', 'Home - IMDB Clone')

@section('content')
<!-- Error Display -->
@if(isset($error) && !empty($error))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-exclamation-triangle me-2"></i>Error!</strong> 
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row mb-4">
    <div class="col-12">
        <div class="p-5 bg-dark text-white rounded">
            <h1 class="display-4">Selamat Datang di IMDB Clone</h1>
            <p class="lead">Database lengkap film, serial TV, dan selebriti</p>
            
            <!-- Stats Cards -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <h2 class="card-title">{{ number_format($stats->total_titles ?? 0) }}</h2>
                            <p class="card-text">Judul Film/TV</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h2 class="card-title">{{ number_format($stats->total_people ?? 0) }}</h2>
                            <p class="card-text">Orang</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h2 class="card-title">{{ number_format($stats->total_genres ?? 0) }}</h2>
                            <p class="card-text">Genre</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Titles Section -->
<div class="row mb-5">
    <div class="col-12">
        <h2 class="border-bottom pb-2 mb-4">
            <i class="fas fa-fire text-danger me-2"></i>Rilis Terbaru
        </h2>
        
        @if(isset($latestTitles) && count($latestTitles) > 0)
        <div class="row">
            @foreach($latestTitles as $title)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card movie-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            @if(isset($title->tconst) && isset($title->primaryTitle))
                            <a href="{{ route('titles.show', $title->tconst) }}" 
                               class="text-decoration-none text-dark">
                                {{ Str::limit($title->primaryTitle ?? 'Untitled', 40) }}
                            </a>
                            @else
                            <span class="text-muted">Title not available</span>
                            @endif
                        </h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>{{ $title->startYear ?? 'N/A' }} 
                                | 
                                <i class="fas fa-film me-1"></i>{{ $title->titleType ?? 'Unknown' }}
                            </small>
                        </p>
                        @if(isset($title->runtimeMinutes) && $title->runtimeMinutes)
                        <p class="card-text">
                            <small>
                                <i class="fas fa-clock me-1"></i>{{ $title->runtimeMinutes }} menit
                            </small>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada data title yang ditemukan.
                @if(isset($error))
                <br><small>Error: {{ $error }}</small>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Top Rated Section -->
@if(isset($topRated) && count($topRated) > 0)
<div class="row">
    <div class="col-12">
        <h2 class="border-bottom pb-2 mb-4">
            <i class="fas fa-star text-warning me-2"></i>Top Rated
        </h2>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Judul</th>
                        <th>Rating</th>
                        <th>Votes</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topRated as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>
                                @if(isset($item->primaryTitle))
                                {{ $item->primaryTitle }}
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </strong>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark fs-6">
                                {{ isset($item->averageRating) ? number_format($item->averageRating, 1) : 'N/A' }}
                            </span>
                        </td>
                        <td>{{ isset($item->numVotes) ? number_format($item->numVotes) : 'N/A' }}</td>
                        <td>
                            @if(isset($item->tconst))
                            <a href="{{ route('titles.show', $item->tconst) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            @else
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                <i class="fas fa-ban"></i> N/A
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Tidak ada data top rated yang tersedia.
        </div>
    </div>
</div>
@endif
@endsection
@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<!-- Error Display -->
@if(isset($error) && !empty($error))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-exclamation-triangle me-2"></i>Error!</strong> 
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-exclamation-triangle me-2"></i>Error!</strong> 
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Loading message untuk JavaScript disabled -->
<noscript>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        JavaScript is disabled. Some features may not work properly.
    </div>
</noscript>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="fas fa-search me-2"></i>Pencarian Film & Series
        </h1>
        
        <!-- SIMPLE Search Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('titles.search') }}" method="GET" id="searchForm">
                    @csrf
                    <div class="input-group input-group-lg">
                        <input type="text" 
                               name="q" 
                               class="form-control" 
                               placeholder="Cari judul film atau series..."
                               value="{{ old('q', $keyword ?? '') }}"
                               required
                               autofocus>
                        <button class="btn btn-warning" type="submit" id="searchBtn">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        Contoh: "Avengers", "2023", "Action", "Tom Hanks"
                    </small>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Results Section - ONLY show if we have a keyword -->
@if(isset($keyword) && $keyword !== '')
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>
                Hasil pencarian untuk "<span class="text-primary">{{ $keyword }}</span>"
            </h3>
            <span class="badge bg-primary fs-6 p-3">
                <i class="fas fa-film me-1"></i>
                {{ count($results) }} hasil
            </span>
        </div>
        
        @if(count($results) > 0)
        <!-- Results Grid -->
        <div class="row">
            @foreach($results as $title)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-secondary mb-2">{{ $title->titleType ?? 'Unknown' }}</span>
                            @if(isset($title->startYear) && $title->startYear)
                            <span class="badge bg-info">{{ $title->startYear }}</span>
                            @endif
                        </div>
                        
                        <h5 class="card-title">
                            <a href="{{ route('titles.show', ['tconst' => $title->tconst]) }}" 
                               class="text-decoration-none text-dark">
                                {{ $title->primaryTitle ?? 'Judul Tidak Tersedia' }}
                            </a>
                        </h5>
                        
                        @if(isset($title->runtimeMinutes) && $title->runtimeMinutes)
                        <p class="card-text text-muted small">
                            <i class="fas fa-clock me-1"></i>
                            {{ $title->runtimeMinutes }} menit
                        </p>
                        @endif
                        
                        <div class="mt-3">
                            <a href="{{ route('titles.show', ['tconst' => $title->tconst]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-info-circle me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- No Results -->
        <div class="card border-warning">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-warning mb-3"></i>
                    <h4 class="text-warning">Tidak ada hasil ditemukan</h4>
                </div>
                <p class="mb-4">
                    Tidak ada hasil untuk "<strong>{{ $keyword }}</strong>"
                </p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>Kembali ke Home
                    </a>
                    <button onclick="document.getElementById('searchForm').reset(); document.querySelector('input[name=\"q\"]').focus();" 
                            class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-2"></i>Cari Lagi
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@else
<!-- Initial Search Instructions -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Cara Mencari
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-film me-2"></i>Contoh Pencarian:</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Judul Film:</strong> "Avengers", "Titanic", "The Godfather"
                            </li>
                            <li class="list-group-item">
                                <strong>Tahun:</strong> "2023", "1990", "2000-2010"
                            </li>
                            <li class="list-group-item">
                                <strong>Genre:</strong> "Action", "Drama", "Comedy"
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-star me-2"></i>Pencarian Populer:</h6>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @php
                                $popular = ['Avengers', 'Star Wars', 'Harry Potter', 'Marvel', '2023', 'Action', 'Comedy'];
                            @endphp
                            @foreach($popular as $term)
                            <a href="{{ route('titles.search') }}?q={{ urlencode($term) }}" 
                               class="btn btn-outline-primary btn-sm">
                                {{ $term }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// Simple loading indicator
document.getElementById('searchForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('searchBtn');
    if (btn) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mencari...';
        btn.disabled = true;
    }
});

// Auto-focus search input
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="fas fa-search me-2"></i>Pencarian
        </h1>
        
        <!-- Search Box -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('titles.search') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text" 
                               name="q" 
                               class="form-control" 
                               placeholder="Masukkan judul film, serial TV, atau kata kunci..."
                               value="{{ $keyword ?? '' }}"
                               required>
                        <button class="btn btn-warning" type="submit">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Results Section -->
@if(!empty($keyword))
<div class="row">
    <div class="col-12">
        <h3 class="mb-3">
            Hasil pencarian untuk: "<strong>{{ $keyword }}</strong>"
            <span class="badge bg-primary fs-6">{{ count($results) }} hasil</span>
        </h3>
        
        @if(count($results) > 0)
        <div class="row">
            @foreach($results as $title)
            <div class="col-md-4 mb-4">
                <div class="card h-100 movie-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('titles.show', $title->tconst) }}" 
                               class="text-decoration-none text-dark">
                                {{ $title->primaryTitle ?? 'No Title' }}
                            </a>
                        </h5>
                        
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $title->titleType ?? 'Unknown' }}</span>
                            @if(!empty($title->startYear))
                            <span class="badge bg-info">{{ $title->startYear }}</span>
                            @endif
                        </div>
                        
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ !empty($title->runtimeMinutes) ? $title->runtimeMinutes . ' menit' : 'N/A' }}
                            </small>
                        </p>
                        
                        <a href="{{ route('titles.show', $title->tconst) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-info-circle me-1"></i>Detail Lengkap
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-warning">
            <h4 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>Tidak ditemukan
            </h4>
            <p>Tidak ada hasil yang cocok dengan kata kunci "<strong>{{ $keyword }}</strong>"</p>
            <hr>
            <p class="mb-0">Coba kata kunci lain atau periksa ejaan Anda.</p>
        </div>
        @endif
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <h4 class="alert-heading">
                <i class="fas fa-info-circle me-2"></i>Tips Pencarian
            </h4>
            <ul>
                <li>Gunakan kata kunci spesifik untuk hasil yang lebih akurat</li>
                <li>Coba cari berdasarkan judul, genre, atau tahun rilis</li>
                <li>Anda juga bisa mencari berdasarkan ID (tconst)</li>
            </ul>
        </div>
    </div>
</div>
@endif
@endsection
@extends('layouts.app')

@section('title', 'Edit Episode')

@section('content')
<style>
    body { background-color: #0f172a !important; }
    .page-header {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
    }
    .form-card {
        background: #1e293b;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    .form-label { color: #e2e8f0; font-weight: 600; margin-bottom: 8px; }
    .form-control {
        background-color: #0f172a;
        border: 1px solid #334155;
        color: #e2e8f0;
        padding: 12px;
        border-radius: 8px;
    }
    .form-control:focus {
        background-color: #0f172a;
        border-color: #f59e0b;
        color: #e2e8f0;
        box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
    }
</style>

<div class="container mt-4 mb-5">
    <div class="page-header">
        <h1 class="mb-1"><i class="fas fa-edit"></i> Edit Episode</h1>
        <p class="mb-0 opacity-75">Update episode information</p>
    </div>

    <div class="form-card">
        <form action="{{ route('production.episodes.update', $episode->tconst) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-key"></i> Episode ID
                </label>
                <input type="text" 
                       class="form-control" 
                       value="{{ $episode->tconst }}"
                       disabled>
                <small class="text-muted">ID cannot be changed</small>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-film"></i> Episode Title
                </label>
                <input type="text" 
                       class="form-control" 
                       value="{{ $episode->episode_title ?? 'Untitled' }}"
                       disabled>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="seasonNumber" class="form-label">
                        <i class="fas fa-layer-group"></i> Season Number *
                    </label>
                    <input type="number" 
                           class="form-control @error('seasonNumber') is-invalid @enderror" 
                           id="seasonNumber" 
                           name="seasonNumber" 
                           value="{{ old('seasonNumber', $episode->seasonNumber) }}"
                           min="1"
                           required>
                    @error('seasonNumber')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="episodeNumber" class="form-label">
                        <i class="fas fa-list-ol"></i> Episode Number *
                    </label>
                    <input type="number" 
                           class="form-control @error('episodeNumber') is-invalid @enderror" 
                           id="episodeNumber" 
                           name="episodeNumber" 
                           value="{{ old('episodeNumber', $episode->episodeNumber) }}"
                           min="1"
                           required>
                    @error('episodeNumber')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning btn-lg">
                    <i class="fas fa-save"></i> Update Episode
                </button>
                <a href="{{ route('production.episodes.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
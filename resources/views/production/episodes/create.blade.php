@extends('layouts.app')

@section('title', 'Add New Episode')

@section('content')
<style>
    body { background-color: #0f172a !important; }
    .page-header {
        background: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
    }
    .form-card {
        background: #1e293b;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    .form-label { color: #e2e8f0; font-weight: 600; margin-bottom: 8px; }
    .form-control, .form-select {
        background-color: #0f172a;
        border: 1px solid #334155;
        color: #e2e8f0;
        padding: 12px;
        border-radius: 8px;
    }
    .form-control:focus, .form-select:focus {
        background-color: #0f172a;
        border-color: #06b6d4;
        color: #e2e8f0;
        box-shadow: 0 0 0 0.2rem rgba(6, 182, 212, 0.25);
    }
    .form-select option {
        background-color: #0f172a;
        color: #e2e8f0;
    }
</style>

<div class="container mt-4 mb-5">
    <div class="page-header">
        <h1 class="mb-1"><i class="fas fa-plus-circle"></i> Add New Episode</h1>
        <p class="mb-0 opacity-75">Create a new episode entry</p>
    </div>

    <div class="form-card">
        <form action="{{ route('production.episodes.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tconst" class="form-label">
                        <i class="fas fa-key"></i> Episode ID (tconst) *
                    </label>
                    <input type="text" 
                           class="form-control @error('tconst') is-invalid @enderror" 
                           id="tconst" 
                           name="tconst" 
                           placeholder="e.g., tt9999999"
                           value="{{ old('tconst') }}"
                           maxlength="10"
                           required>
                    @error('tconst')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="parentTconst" class="form-label">
                        <i class="fas fa-tv"></i> Parent Series *
                    </label>
                    <select class="form-select @error('parentTconst') is-invalid @enderror" 
                            id="parentTconst" 
                            name="parentTconst"
                            required>
                        <option value="">Select TV Series</option>
                        @foreach($tvSeries as $series)
                        <option value="{{ $series->tconst }}" 
                                {{ old('parentTconst') == $series->tconst ? 'selected' : '' }}>
                            {{ $series->primaryTitle }}
                        </option>
                        @endforeach
                    </select>
                    @error('parentTconst')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
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
                           placeholder="e.g., 1"
                           value="{{ old('seasonNumber', 1) }}"
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
                           placeholder="e.g., 1"
                           value="{{ old('episodeNumber', 1) }}"
                           min="1"
                           required>
                    @error('episodeNumber')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-info btn-lg">
                    <i class="fas fa-save"></i> Save Episode
                </button>
                <a href="{{ route('production.episodes.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
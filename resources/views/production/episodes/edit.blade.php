@extends('layouts.app')

@section('title', 'Edit Episode')

@section('content')
<style>
    /* === THEME CONFIGURATION (SHOWFY STYLE) === */
    :root {
        --bg-main: #0d0d0d;
        --bg-card: #141414;
        --primary-pink: #d95f8c;
        --primary-red: #870339;
        --border-color: rgba(255, 255, 255, 0.1);
    }
    
    body { background-color: var(--bg-main) !important; color: white !important; }

    .form-label { color: #a3a3a3; font-size: 0.9rem; margin-bottom: 8px; }
    
    /* Custom Input Style */
    .form-control-custom {
        background-color: #0d0d0d;
        border: 1px solid var(--border-color);
        color: white;
        padding: 12px 15px;
        border-radius: 10px;
    }
    
    .form-control-custom:focus {
        background-color: #000;
        border-color: var(--primary-pink);
        box-shadow: 0 0 10px rgba(217, 95, 140, 0.2);
        color: white;
        outline: none;
    }

    /* Style khusus Readonly (Biar kelihatan mati tapi tetap estetik) */
    .form-control-custom:disabled, .form-control-custom:read-only {
        background-color: #1a1a1a;
        border-color: #333;
        color: #777;
        cursor: not-allowed;
        font-style: italic;
    }

    /* Tombol Gradient Pink */
    .btn-gradient-pink {
        background: linear-gradient(135deg, var(--primary-red), var(--primary-pink));
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(135, 3, 57, 0.3);
        transition: 0.3s;
    }
    
    .btn-gradient-pink:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(217, 95, 140, 0.4);
        color: white;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg" style="background: var(--bg-card); border-radius: 20px;">
                
                {{-- HEADER --}}
                <div class="card-header border-0 bg-transparent py-4 px-4">
                    <h4 class="mb-0 fw-bold text-white">
                        <i class="fas fa-edit me-2" style="color: var(--primary-pink)"></i> Edit Episode
                    </h4>
                </div>
                
                {{-- BODY --}}
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('production.episodes.update', $episode->tconst) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- 1. INFO EPISODE (READ ONLY) --}}
                        {{-- GANTI INPUT TITLE YANG SEBELUMNYA READONLY DENGAN INI: --}}
<div class="col-md-8 mb-4">
    <label class="form-label">Episode Title</label>
    <input type="text" 
           class="form-control form-control-custom @error('primaryTitle') is-invalid @enderror" 
           name="primaryTitle" 
           value="{{ old('primaryTitle', $episode->episode_title) }}" 
           required> {{-- SEKARANG BISA DIEDIT --}}
    @error('primaryTitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Tambahkan juga Input Runtime di bawahnya jika mau --}}

                            <div class="col-md-8 mb-4">
                                <label class="form-label">Episode Title</label>
                                <input type="text" 
                                       class="form-control form-control-custom" 
                                       value="{{ $episode->episode_title ?? 'Untitled' }}" 
                                       readonly>
                                <div class="form-text text-muted small mt-1">Judul diedit via menu Movies/Titles.</div>
                            </div>
                        </div>

                        {{-- 2. SEASON & NOMOR EPISODE (EDITABLE) --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="seasonNumber" class="form-label">Season Number</label>
                                <input type="number" 
                                       class="form-control form-control-custom @error('seasonNumber') is-invalid @enderror" 
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
                                <label for="episodeNumber" class="form-label">Episode Number</label>
                                <input type="number" 
                                       class="form-control form-control-custom @error('episodeNumber') is-invalid @enderror" 
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

                        {{-- TOMBOL AKSI --}}
                        <div class="d-flex justify-content-between pt-3">
                            <a href="{{ route('production.episodes.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-gradient-pink px-5 rounded-pill fw-bold">
                                Update Episode <i class="fas fa-save ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
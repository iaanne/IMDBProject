@extends('layouts.app')

@section('title', 'Add New Movie')

@section('content')
<style>
    /* KONFIGURASI WARNA */
    :root {
        --bg-main: #0d0d0d;
        --bg-card: #141414;
        --primary-pink: #d95f8c;
        --primary-red: #870339;
        --border-color: rgba(255, 255, 255, 0.1);
    }
    
    .form-label { color: #a3a3a3; font-size: 0.9rem; margin-bottom: 8px; }
    
    /* Input Style Custom Showfy */
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

    .form-check-input:checked {
        background-color: var(--primary-pink);
        border-color: var(--primary-pink);
    }
    
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
                
                <div class="card-header border-0 bg-transparent py-4 px-4">
                    <h4 class="mb-0 fw-bold text-white">
                        <i class="fas fa-plus-circle me-2" style="color: var(--primary-pink)"></i> Add New Movie
                    </h4>
                </div>
                
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('production.movies.store') }}" method="POST">
                        @csrf
                        
                        {{-- ID Film --}}
                        <div class="mb-4">
                            <label for="tconst" class="form-label">Movie ID (tconst)</label>
                            <input type="text" class="form-control form-control-custom @error('tconst') is-invalid @enderror" 
                                   id="tconst" name="tconst" value="{{ old('tconst', 'tt' . rand(1000000, 9999999)) }}" required>
                            <div class="form-text text-muted small mt-2">Format: tt + angka (contoh: tt1234567)</div>
                        </div>

                        {{-- Judul Utama --}}
                        <div class="mb-4">
                            <label for="primaryTitle" class="form-label">Primary Title (Judul Tampil)</label>
                            <input type="text" class="form-control form-control-custom" id="primaryTitle" name="primaryTitle" value="{{ old('primaryTitle') }}" required placeholder="Contoh: Avengers: Endgame">
                        </div>

                        {{-- Judul Asli (BARU) --}}
                        <div class="mb-4">
                            <label for="originalTitle" class="form-label">Original Title (Optional)</label>
                            <input type="text" class="form-control form-control-custom" id="originalTitle" name="originalTitle" value="{{ old('originalTitle') }}" placeholder="Contoh: Avengers: Endgame (sama jika tidak ada beda)">
                            <div class="form-text text-muted small">Kosongkan jika sama dengan Primary Title.</div>
                        </div>

                        <div class="row">
                            {{-- Tahun --}}
                            <div class="col-md-6 mb-4">
                                <label for="startYear" class="form-label">Release Year</label>
                                <input type="number" class="form-control form-control-custom" id="startYear" name="startYear" value="{{ old('startYear', date('Y')) }}" min="1800" max="2100" required>
                            </div>

                            {{-- Durasi (BARU) --}}
                            <div class="col-md-6 mb-4">
                                <label for="runtimeMinutes" class="form-label">Runtime (Minutes)</label>
                                <input type="number" class="form-control form-control-custom" id="runtimeMinutes" name="runtimeMinutes" value="{{ old('runtimeMinutes') }}" placeholder="Contoh: 120" min="1">
                            </div>
                        </div>

                        {{-- Checkbox Dewasa (BARU) --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="isAdult" name="isAdult" value="1" {{ old('isAdult') ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="isAdult">Adult Movie (18+)</label>
                            </div>
                        </div>

                        {{-- Genre --}}
                        <div class="mb-4">
                            <label class="form-label d-block mb-2">Select Genres</label>
                            <div class="p-3" style="background: #0d0d0d; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; max-height: 200px; overflow-y: auto;">
                                <div class="row g-2">
                                    @foreach($genres as $genre)
                                    <div class="col-md-4 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="genres[]" 
                                                   value="{{ $genre->genre_id }}" id="genre_{{ $genre->genre_id }}">
                                            <label class="form-check-label text-white small" for="genre_{{ $genre->genre_id }}">
                                                {{ $genre->genre_name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-between pt-3">
                            <a href="{{ route('production.movies.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
                            <button type="submit" class="btn btn-gradient-pink px-5 rounded-pill fw-bold">
                                Save Movie <i class="fas fa-check ms-2"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
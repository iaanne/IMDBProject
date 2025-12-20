@extends('layouts.app')

@section('title', 'Add New Movie')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow">
                <div class="card-header border-secondary bg-transparent py-3">
                    <h4 class="mb-0"><i class="fas fa-plus-circle text-primary"></i> Add New Movie</h4>
                </div>
                
                <div class="card-body">
                    {{-- Form mengarah ke route store --}}
                    <form action="{{ route('production.movies.store') }}" method="POST">
                        @csrf
                        
                        {{-- ID Film (tconst) --}}
                        <div class="mb-3">
                            <label for="tconst" class="form-label text-muted">Movie ID (tconst)</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary @error('tconst') is-invalid @enderror" 
                                   id="tconst" name="tconst" value="{{ old('tconst', 'tt' . rand(1000000, 9999999)) }}" required>
                            <div class="form-text text-secondary">Unique ID from IMDb (e.g., tt1234567)</div>
                            @error('tconst')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Judul Film --}}
                        <div class="mb-3">
                            <label for="primaryTitle" class="form-label text-muted">Primary Title</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary @error('primaryTitle') is-invalid @enderror" 
                                   id="primaryTitle" name="primaryTitle" value="{{ old('primaryTitle') }}" required placeholder="Enter movie title...">
                            @error('primaryTitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tahun Rilis --}}
                        <div class="mb-3">
                            <label for="startYear" class="form-label text-muted">Release Year</label>
                            <input type="number" class="form-control bg-dark text-white border-secondary @error('startYear') is-invalid @enderror" 
                                   id="startYear" name="startYear" value="{{ old('startYear', date('Y')) }}" min="1800" max="2100" required>
                            @error('startYear')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Genre Selection (Checkbox) --}}
                        <div class="mb-4">
                            <label class="form-label text-muted d-block mb-2">Genres</label>
                            <div class="card bg-dark border-secondary p-3" style="max-height: 200px; overflow-y: auto;">
                                <div class="row g-2">
                                    @foreach($genres as $genre)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input bg-dark border-secondary" type="checkbox" 
                                                   name="genres[]" value="{{ $genre->genre_id }}" id="genre_{{ $genre->genre_id }}">
                                            <label class="form-check-label text-light" for="genre_{{ $genre->genre_id }}">
                                                {{ $genre->genre_name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Action --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('production.movies.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save"></i> Save Movie
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
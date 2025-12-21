@extends('layouts.app')

@section('title', 'Edit Show')

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
    
    .form-control-custom, .form-select-custom {
        background-color: #0d0d0d;
        border: 1px solid var(--border-color);
        color: white;
        padding: 12px 15px;
        border-radius: 10px;
    }
    
    .form-control-custom:focus, .form-select-custom:focus {
        background-color: #000;
        border-color: var(--primary-pink);
        box-shadow: 0 0 10px rgba(217, 95, 140, 0.2);
        color: white;
        outline: none;
    }

    /* Style khusus Readonly */
    .form-control-custom:read-only {
        background-color: #1a1a1a;
        border-color: #333;
        color: #777;
        cursor: not-allowed;
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
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg" style="background: var(--bg-card); border-radius: 20px;">
                
                <div class="card-header border-0 bg-transparent py-4 px-4">
                    <h4 class="mb-0 fw-bold text-white">
                        <i class="fas fa-edit me-2" style="color: var(--primary-pink)"></i> Edit TV Show
                    </h4>
                </div>
                
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('production.shows.update', $show->show_id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- PENTING: Method Spoofing untuk Update --}}
                        
                        {{-- === BAGIAN 1: INFORMASI UTAMA === --}}
                        <div class="row">
                            {{-- Show ID (READ ONLY) --}}
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Show ID (Tidak bisa diubah)</label>
                                <input type="number" class="form-control form-control-custom" 
                                       value="{{ $show->show_id }}" readonly>
                            </div>

                            {{-- Show Name --}}
                            <div class="col-md-8 mb-4">
                                <label class="form-label">Show Name (Judul Utama)</label>
                                <input type="text" class="form-control form-control-custom" 
                                       name="name" value="{{ old('name', $show->name) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Original Name --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Original Name</label>
                                <input type="text" class="form-control form-control-custom" 
                                       name="original_name" value="{{ old('original_name', $show->original_name) }}">
                            </div>
                            
                            {{-- Tagline --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Tagline</label>
                                <input type="text" class="form-control form-control-custom" 
                                       name="tagline" value="{{ old('tagline', $show->tagline) }}">
                            </div>
                        </div>

                        {{-- === BAGIAN 2: STATISTIK & TIPE === --}}
                        <h6 class="text-white mt-3 mb-3 border-bottom border-secondary pb-2">Details & Classification</h6>

                        <div class="row">
                            {{-- Type Dropdown --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Show Type</label>
                                <select class="form-select form-select-custom" name="type_id">
                                    <option value="">-- Pilih Tipe --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->type_id }}" 
                                            {{ (old('type_id', $show->type_id) == $type->type_id) ? 'selected' : '' }}>
                                            {{ $type->type_name ?? $type->type_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Dropdown --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-custom" name="status_id">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->status_id }}" 
                                            {{ (old('status_id', $show->status_id) == $status->status_id) ? 'selected' : '' }}>
                                            {{ $status->status_name ?? $status->status_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            {{-- Seasons --}}
                            <div class="col-md-3 mb-4">
                                <label class="form-label">Seasons</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="number_of_seasons" value="{{ old('number_of_seasons', $show->number_of_seasons) }}">
                            </div>

                            {{-- Episodes --}}
                            <div class="col-md-3 mb-4">
                                <label class="form-label">Episodes</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="number_of_episodes" value="{{ old('number_of_episodes', $show->number_of_episodes) }}">
                            </div>

                            {{-- Runtime --}}
                            <div class="col-md-3 mb-4">
                                <label class="form-label">Runtime (Menit)</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="episode_run_time" value="{{ old('episode_run_time', $show->episode_run_time) }}">
                            </div>

                            {{-- Popularity --}}
                            <div class="col-md-3 mb-4">
                                <label class="form-label">Popularity Score</label>
                                <input type="number" step="0.1" class="form-control form-control-custom" 
                                       name="popularity" value="{{ old('popularity', $show->popularity) }}">
                            </div>
                        </div>

                        {{-- === BAGIAN 3: STATUS (Fixed Layout) === --}}
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="d-flex align-items-center p-3 border border-secondary rounded" style="background-color: rgba(255,255,255,0.05);">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="adult" name="adult" value="1" 
                                               style="width: 3em; height: 1.5em; margin-top: 0; cursor: pointer;"
                                               {{ (old('adult', $show->adult) == 1) ? 'checked' : '' }}>
                                        <label class="form-check-label text-white ms-3 fw-bold" for="adult" style="cursor: pointer;">
                                            Adult Content (18+)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border border-secondary rounded" style="background-color: rgba(255,255,255,0.05);">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="in_production" name="in_production" value="1" 
                                               style="width: 3em; height: 1.5em; margin-top: 0; cursor: pointer;"
                                               {{ (old('in_production', $show->in_production) == 1) ? 'checked' : '' }}>
                                        <label class="form-check-label text-white ms-3 fw-bold" for="in_production" style="cursor: pointer;">
                                            In Production
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- OVERVIEW --}}
                        <div class="mb-4">
                            <label class="form-label">Overview</label>
                            <textarea class="form-control form-control-custom" name="overview" rows="5">{{ old('overview', $show->overview) }}</textarea>
                        </div>

                        {{-- TOMBOL --}}
                        <div class="d-flex justify-content-between pt-3">
                            <a href="{{ route('production.shows.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
                            <button type="submit" class="btn btn-gradient-pink px-5 rounded-pill fw-bold">
                                Update Show <i class="fas fa-save ms-2"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
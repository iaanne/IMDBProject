@extends('layouts.app')

@section('title', 'Add New Episode')

@section('content')
{{-- Load CSS Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>

      :root {
        --bg-main: #0d0d0d;
        --bg-card: #000000;
        --primary-pink: #d95f8c;
        --primary-red: #870339;
        --text-muted: #a3a3a3;
        --border-color: rgba(255, 255, 255, 0.15);
    }
    /* === UI THEME ADJUSTMENTS === */
    .card-custom {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .form-label {
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-control-custom {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--border-color) !important;
        color: white !important;
        border-radius: 12px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        border-color: var(--primary-pink) !important;
        box-shadow: 0 0 0 0.25 row rgba(217, 95, 140, 0.25) !important;
        background-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* === SELECT2 DARK THEME CUSTOMIZATION === */
    .select2-container--default .select2-selection--single {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--border-color) !important;
        height: 50px !important;
        border-radius: 12px !important;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white !important;
        padding-left: 15px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
    }

    .select2-dropdown {
        background-color: #1a1a1a !important;
        border: 1px solid var(--primary-pink) !important;
        color: white !important;
        z-index: 9999;
    }

    .select2-search__field {
        background-color: #2d2d2d !important;
        border: 1px solid var(--border-color) !important;
        color: white !important;
        border-radius: 8px !important;
    }

    .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary-pink) !important;
    }

    .btn-gradient-pink {
        background: linear-gradient(45deg, #870339, #d95f8c);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-gradient-pink:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(217, 95, 140, 0.4);
        color: white;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom">
                
                <div class="card-header border-0 bg-transparent py-4 px-4 d-flex align-items-center">
                    <div class="icon-box me-3" style="background: rgba(217, 95, 140, 0.1); padding: 10px; border-radius: 12px;">
                        <i class="fas fa-plus-circle fa-lg" style="color: var(--primary-pink)"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-white">Add New Episode</h4>
                        <p class="text-white small mb-0">Input detail episode baru untuk serial TV yang sudah ada.</p>
                    </div>
                </div>
                
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('production.episodes.store') }}" method="POST">
                        @csrf
                        
                        {{-- 1. PARENT SERIES (SELECT2) --}}
                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-tv me-2"></i>Parent Series (TV Show)</label>
                            <select class="form-control" name="parentTconst" id="seriesSearch" required>
                                <option value="">-- Ketik Judul Serial TV --</option>
                            </select>
                            @error('parentTconst') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <hr style="border-color: var(--border-color); margin: 30px 0;">

                        {{-- 2. EPISODE DETAILS --}}
                        <div class="row">
                            <div class="col-md-5 mb-4">
                                <label class="form-label"><i class="fas fa-fingerprint me-2"></i>Episode ID (tconst)</label>
                                <input type="text" class="form-control form-control-custom @error('tconst') is-invalid @enderror" 
                                       name="tconst" value="{{ old('tconst') }}" placeholder="Contoh: tt1234567" maxlength="10" required>
                                @error('tconst') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-7 mb-4">
                                <label class="form-label"><i class="fas fa-heading me-2"></i>Episode Title</label>
                                <input type="text" class="form-control form-control-custom" 
                                       name="primaryTitle" value="{{ old('primaryTitle') }}" placeholder="Contoh: The Pilot / Winter is Coming" required>
                            </div>
                        </div>

                        {{-- 3. SEASON, NUMBER, RUNTIME --}}
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><i class="fas fa-layer-group me-2"></i>Season No.</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="seasonNumber" value="{{ old('seasonNumber', 1) }}" min="1" required>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label"><i class="fas fa-list-ol me-2"></i>Episode No.</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="episodeNumber" value="{{ old('episodeNumber', 1) }}" min="1" required>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label"><i class="fas fa-clock me-2"></i>Runtime (Min)</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="runtimeMinutes" value="{{ old('runtimeMinutes') }}" placeholder="45">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-4">
                            <a href="{{ route('production.episodes.index') }}" class="text-decoration-none text-white">
                                <i class="fas fa-arrow-left me-1"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-gradient-pink px-5 rounded-pill fw-bold py-3">
                                <i class="fas fa-save me-2"></i>Save Episode
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#seriesSearch').select2({
        placeholder: 'Cari judul serial (misal: Arcane, One Piece)...',
        allowClear: true,
        width: '100%',
        ajax: {
            url: '{{ route("api.searchSeries") }}',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
});
</script>
@endsection
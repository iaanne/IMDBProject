@extends('layouts.app')

@section('title', 'Add New Episode')

@section('content')
{{-- Load CSS Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- CSS Tambahan biar Select2 warnanya gelap/sesuai tema (Opsional) --}}
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da; /* Sesuaikan border kamu */
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        color: #000; /* Warna teks dropdown */
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg" style="background: var(--bg-card); border-radius: 20px;">
                
                <div class="card-header border-0 bg-transparent py-4 px-4">
                    <h4 class="mb-0 fw-bold text-white">
                        <i class="fas fa-plus-circle me-2" style="color: var(--primary-pink)"></i> Add New Episode
                    </h4>
                </div>
                
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('production.episodes.store') }}" method="POST">
                        @csrf
                        
                        {{-- 1. PARENT SERIES (SUDAH DIUBAH PAKAI SELECT2) --}}
                        <div class="mb-4">
                            <label class="form-label">Parent Series (TV Show)</label>
                            {{-- Perhatikan ID="seriesSearch" --}}
                            <select class="form-control" name="parentTconst" id="seriesSearch" required>
                                <option value="">-- Ketik Judul Serial TV --</option>
                                {{-- Kita hapus @foreach disini karena data akan diambil via AJAX --}}
                            </select>
                            @error('parentTconst') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- 2. EPISODE DETAILS (JUDUL & ID) --}}
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Episode ID (tconst)</label>
                                <input type="text" class="form-control form-control-custom @error('tconst') is-invalid @enderror" 
                                       name="tconst" value="{{ old('tconst') }}" placeholder="tt9999999" maxlength="10" required>
                            </div>
                            <div class="col-md-8 mb-4">
                                <label class="form-label">Episode Title (Judul)</label>
                                <input type="text" class="form-control form-control-custom" 
                                       name="primaryTitle" value="{{ old('primaryTitle') }}" placeholder="Contoh: The Pilot" required>
                            </div>
                        </div>

                        {{-- 3. SEASON, NUMBER, RUNTIME --}}
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Season No.</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="seasonNumber" value="{{ old('seasonNumber', 1) }}" min="1" required>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label">Episode No.</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="episodeNumber" value="{{ old('episodeNumber', 1) }}" min="1" required>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label">Runtime (Menit)</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="runtimeMinutes" value="{{ old('runtimeMinutes') }}" placeholder="45">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3">
                            <a href="{{ route('production.episodes.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
                            <button type="submit" class="btn btn-gradient-pink px-5 rounded-pill fw-bold">Save Episode</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT BAGIAN BAWAH --}}
{{-- Load jQuery & Select2 JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 pada elemen ID #seriesSearch
    $('#seriesSearch').select2({
        placeholder: 'Ketik judul serial (contoh: Breaking Bad)...',
        allowClear: true,
        width: '100%', // Biar lebar full
        ajax: {
            url: '{{ route("api.searchSeries") }}', // Panggil Route API
            dataType: 'json',
            delay: 250, // Delay sedikit biar gak spam server pas ngetik
            data: function (params) {
                return {
                    q: params.term // Kirim apa yang diketik user
                };
            },
            processResults: function (data) {
                return {
                    results: data // Tampilkan hasil dari controller
                };
            },
            cache: true
        }
    });
});
</script>

@endsection
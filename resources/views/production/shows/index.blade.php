@extends('layouts.app')

@section('title', 'Shows Management')

@section('content')
<style>
    /* === THEME CONFIGURATION === */
    :root {
        --bg-main: #0d0d0d;
        --bg-card: #141414;
        --primary-pink: #d95f8c;
        --primary-red: #870339;
        --text-muted: #a3a3a3;
        --border-color: rgba(255, 255, 255, 0.1);
    }

    body { background-color: var(--bg-main) !important; color: white !important; overflow-x: hidden; }
    
    /* Header Gradient */
    .page-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-pink) 100%);
        color: white;
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(135, 3, 57, 0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .content-card {
        background: var(--bg-card) !important;
        border-radius: 20px;
        padding: 25px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }
    
    /* === TABEL ANTI-OFFSIDE === */
    .table-responsive {
        overflow-x: auto;
    }

    .table-dark-custom {
        width: 100%;
        border-collapse: separate; 
        border-spacing: 0;
        table-layout: fixed; /* KUNCI: Kolom patuh pada lebar % */
    }

    /* Header Styling */
    .table-dark-custom thead th {
        background-color: #1f1f1f !important; 
        color: #a3a3a3 !important;
        border-bottom: 2px solid #333;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        padding: 15px;
        vertical-align: middle;
    }

    /* Body Styling */
    .table-dark-custom tbody td {
        background-color: var(--bg-card) !important;
        color: white !important;
        border-bottom: 1px solid var(--border-color);
        padding: 15px;
        vertical-align: top;
        
        /* Agar teks panjang turun ke bawah (Word Wrap) */
        white-space: normal !important;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .table-dark-custom tbody tr:hover td {
        background-color: #2a2a2a !important;
    }

    /* Badge Styles */
    .badge-adult { background-color: #dc2626; color: white; font-size: 0.7rem; } /* Merah */
    .badge-safe { background-color: #10b981; color: white; font-size: 0.7rem; }  /* Hijau */
    .badge-prod { background-color: #3b82f6; color: white; font-size: 0.7rem; }  /* Biru */
    .badge-ended { background-color: #64748b; color: white; font-size: 0.7rem; } /* Abu */

    .btn-add-new {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        backdrop-filter: blur(5px);
        transition: 0.3s;
    }
    .btn-add-new:hover {
        background: white;
        color: var(--primary-red);
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid mt-4 mb-5">
    
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-1 fw-bold"><i class="fas fa-tv me-2"></i> Shows Management</h1>
                <p class="mb-0 opacity-75">Kelola database TV Shows</p>
            </div>
            <a href="{{ route('production.shows.create') }}" class="btn btn-add-new btn-lg px-4 rounded-pill">
                <i class="fas fa-plus me-2"></i> Add New Show
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show bg-dark text-white border-success" role="alert">
        <i class="fas fa-check-circle text-success me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        {{-- ATUR LEBAR KOLOM DI SINI (Total 100%) --}}
                        <th style="width: 8%;" class="ps-4">ID</th>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 45%;">Overview</th>
                        <th style="width: 15%;">Info / Status</th> {{-- Kolom Gabungan --}}
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shows as $show)
                    <tr>
                        {{-- 1. ID --}}
                        <td class="ps-4">
                            <code style="color: var(--primary-pink); font-weight: bold;">{{ $show->show_id }}</code>
                        </td>
                        
                        {{-- 2. Name --}}
                        <td>
                            <strong class="text-white fs-5 d-block">{{ $show->name }}</strong>
                            @if(!empty($show->original_name) && $show->original_name != $show->name)
                                <small class="text-muted d-block mt-1 fst-italic">({{ $show->original_name }})</small>
                            @endif
                        </td>
                        
                        {{-- 3. Overview (Teks Turun ke Bawah) --}}
                        <td>
                            @if(empty($show->overview))
                                <span class="text-white-muted small fst-italic opacity-50">-- Tidak ada deskripsi --</span>
                            @else
                                <span style="color: #cbd5e1 !important; line-height: 1.5; font-size: 0.9rem;">
                                    {{ \Illuminate\Support\Str::limit($show->overview, 150) }}
                                </span>
                            @endif
                        </td>

                        {{-- 4. Status & Info (Gabungan Badge) --}}
                        <td>
                            <div class="d-flex flex-column gap-2">
                                {{-- Badge Adult --}}
                                <div>
                                    @if($show->adult == 1)
                                        <span class="badge badge-adult rounded-pill"><i class="fas fa-exclamation-triangle me-1"></i> 18+</span>
                                    @else
                                        <span class="badge badge-safe rounded-pill"><i class="fas fa-check-circle me-1"></i> General</span>
                                    @endif
                                </div>

                                {{-- Badge Production --}}
                                <div>
                                    @if($show->in_production == 1)
                                        <span class="badge badge-prod rounded-pill"><i class="fas fa-video me-1"></i> On Air</span>
                                    @else
                                        <span class="badge badge-ended rounded-pill"><i class="fas fa-stop-circle me-1"></i> Ended</span>
                                    @endif
                                </div>
                                
                                {{-- Info Seasons --}}
                                @if(isset($show->number_of_seasons) && $show->number_of_seasons > 0)
                                    <small class="text-white-muted mt-1">
                                        <i class="fas fa-layer-group text-secondary"></i> {{ $show->number_of_seasons }} Seasons
                                    </small>
                                @endif
                            </div>
                        </td>
                        
                        {{-- 5. Action --}}
                        <td class="text-center">
                            <a href="{{ route('production.shows.edit', $show->show_id) }}" 
                               class="btn btn-outline-warning btn-sm rounded-circle" 
                               style="width: 35px; height: 35px; border: 1px solid rgba(255, 193, 7, 0.5);" 
                               title="Edit Show">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-white-center text-white-muted py-5">
                            <i class="fas fa-tv fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada data TV Show.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Episodes Management')

@section('content')
<style>
    /* === THEME CONFIGURATION (SHOWFY STYLE) === */
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
        table-layout: fixed; /* KUNCI: Agar kolom rapi dan tidak melebar */
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
        vertical-align: middle;
        
        /* Agar teks panjang turun ke bawah */
        white-space: normal !important;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .table-dark-custom tbody tr:hover td {
        background-color: #2a2a2a !important;
    }
    
    /* Tombol Add New */
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
    
    /* Badge Season/Episode Style */
    .badge-number {
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border-color);
        color: #e2e8f0;
        font-weight: 500;
        padding: 5px 10px;
        min-width: 50px;
    }
</style>

<div class="container-fluid mt-4 mb-5">
    
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-1 fw-bold"><i class="fas fa-list-ol me-2"></i> Episodes Management</h1>
                <p class="mb-0 opacity-75">Kelola data episode TV Show</p>
            </div>
            <a href="{{ route('production.episodes.create') }}" class="btn btn-add-new btn-lg px-4 rounded-pill">
                <i class="fas fa-plus me-2"></i> Add New Episode
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
                        <th style="width: 12%;" class="ps-4">Episode ID</th>
                        <th style="width: 30%;">Episode Title</th>
                        <th style="width: 25%;">Series</th>
                        <th style="width: 10%;" class="text-center">Season</th>
                        <th style="width: 10%;" class="text-center">Episode</th>
                        <th style="width: 13%;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($episodes as $episode)
                    <tr>
                        {{-- ID --}}
                        <td class="ps-4">
                            <code style="color: var(--primary-pink); font-weight: bold;">{{ $episode->tconst }}</code>
                        </td>
                        
                        {{-- Title --}}
                        <td>
                            <strong class="text-white fs-6">{{ $episode->episode_title ?? 'Untitled' }}</strong>
                        </td>
                        
                        {{-- Series Name --}}
                        <td>
                            <span class="text-white small">
                                <i class="fas fa-tv me-1 opacity-50"></i>
                                {{ \Illuminate\Support\Str::limit($episode->series_title ?? 'Unknown Series', 40) }}
                            </span>
                        </td>
                        
                        {{-- Season --}}
                        <td class="text-center">
                            <span class="badge badge-number rounded-pill">S{{ $episode->seasonNumber }}</span>
                        </td>
                        
                        {{-- Episode --}}
                        <td class="text-center">
                            <span class="badge badge-number rounded-pill">E{{ $episode->episodeNumber }}</span>
                        </td>
                        
                        {{-- Actions --}}
                        <td class="text-center">
                            <a href="{{ route('production.episodes.edit', $episode->tconst) }}" 
                               class="btn btn-outline-warning btn-sm rounded-circle" 
                               style="width: 35px; height: 35px; border: 1px solid rgba(255, 193, 7, 0.5);" 
                               title="Edit Episode">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-list-ol fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada data episode.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
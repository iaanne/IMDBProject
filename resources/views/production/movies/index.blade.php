@extends('layouts.app')

@section('title', 'Movies Management')

@section('content')
<style>
    /* === THEME CONFIGURATION (FORCE DARK MODE) === */
    :root {
        --bg-main: #0d0d0d;
        --bg-card: #141414;
        --primary-pink: #d95f8c;
        --primary-red: #870339;
        --text-muted: #a3a3a3;
        --border-color: rgba(255, 255, 255, 0.1);
    }

    /* Paksa background body hitam */
    body { background-color: var(--bg-main) !important; color: white !important; }
    
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
    
    /* Card Container */
    .content-card {
        background: var(--bg-card) !important; /* Paksa Hitam */
        border-radius: 20px;
        padding: 25px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }
    
    /* === TABEL DARK MODE (SUPAYA TULISAN KELIHATAN) === */
    .table-dark-custom {
        width: 100%;
        margin-bottom: 0;
        border-color: var(--border-color);
    }

    /* Header Tabel */
    .table-dark-custom thead th {
        background-color: #1f1f1f !important; 
        color: #a3a3a3 !important;
        border-bottom: 2px solid #333;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 15px;
    }

    /* Body Tabel (Fix Tulisan Hilang) */
    .table-dark-custom tbody td {
        background-color: var(--bg-card) !important; /* Paksa Background Gelap */
        color: white !important; /* Paksa Tulisan Putih */
        border-bottom: 1px solid var(--border-color);
        padding: 15px;
        vertical-align: middle;
    }

    /* Hover Effect */
    .table-dark-custom tbody tr:hover td {
        background-color: #2a2a2a !important;
    }

    /* === BARIS NEW MOVIE (Merah Gelap) === */
    .row-new-movie td {
        background-color: #38101f !important; /* Latar Merah Gelap */
        border-top: 1px solid var(--primary-pink);
        border-bottom: 1px solid var(--primary-pink);
    }
    
    /* Garis Pinggir Pink */
    .row-new-movie td:first-child {
        border-left: 4px solid var(--primary-pink) !important;
    }

    /* Badge NEW */
    .badge-new {
        background: var(--primary-pink);
        color: white;
        font-size: 0.65rem;
        padding: 3px 8px;
        border-radius: 4px;
        margin-left: 10px;
        animation: pulse-glow 2s infinite;
        font-weight: bold;
        vertical-align: middle;
        text-transform: uppercase;
        display: inline-block;
    }

    @keyframes pulse-glow {
        0% { box-shadow: 0 0 0 0 rgba(217, 95, 140, 0.7); }
        70% { box-shadow: 0 0 0 6px rgba(217, 95, 140, 0); }
        100% { box-shadow: 0 0 0 0 rgba(217, 95, 140, 0); }
    }

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
                <h1 class="mb-1 fw-bold"><i class="fas fa-film me-2"></i> Movies Management</h1>
                <p class="mb-0 opacity-75">Kelola koleksi film Showfy</p>
            </div>
            <a href="{{ route('production.movies.create') }}" class="btn btn-add-new btn-lg px-4 rounded-pill">
                <i class="fas fa-plus me-2"></i> Add New Movie
            </a>
        </div>
    </div>

    {{-- ALERT SUDAH DIHAPUS DARI SINI AGAR TIDAK DOUBLE --}}

    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th width="120" class="ps-4">ID</th>
                        <th>Title</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Rating</th>
                        <th class="text-center">Votes</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies as $movie)
                    
                    {{-- LOGIKA CEK MOVIE BARU --}}
                    @php
                        $isNew = false;
                        // Cek apakah property created_at ada (antisipasi jika null/tidak ada)
                        if (isset($movie->created_at)) {
                            try {
                                $isNew = \Carbon\Carbon::parse($movie->created_at)->diffInHours(now()) < 24;
                            } catch (\Exception $e) { $isNew = false; }
                        }
                    @endphp

                    <tr class="{{ $isNew ? 'row-new-movie' : '' }}">
                        <td class="ps-4">
                            <code style="color: var(--primary-pink); font-weight: bold; font-size: 0.9rem;">{{ $movie->tconst }}</code>
                        </td>
                        <td>
                            <strong class="text-white fs-5">{{ $movie->primaryTitle }}</strong>
                            @if($isNew) <span class="badge-new">BARU</span> @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-dark border border-secondary text-light px-3 py-2">{{ $movie->startYear }}</span>
                        </td>
                        <td class="text-center">
                            @if(isset($movie->rating) && $movie->rating > 0)
                                <span class="text-warning fw-bold">
                                    <i class="fas fa-star me-1"></i>{{ number_format($movie->rating, 1) }}
                                </span>
                            @else
                                <span class="text-white small opacity-50">N/A</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(isset($movie->votes) && $movie->votes > 0)
                                <span class="text-white small">{{ number_format($movie->votes) }}</span>
                            @else
                                <span class="text-white small opacity-50">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('production.movies.destroy', $movie->tconst) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Hapus film ini?');"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 35px; height: 35px; border: 1px solid rgba(220,53,69,0.5);">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-white py-5">
                            <i class="fas fa-film fa-3x mb-3 opacity-25"></i>
                            <p>Belum ada data film.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Safe Check --}}
        @if($movies instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="mt-4 d-flex justify-content-end">
                {{ $movies->links() }}
            </div>
        @endif
        
    </div>
</div>
@endsection
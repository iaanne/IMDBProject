@extends('layouts.app')

@section('title', 'Production Dashboard')

@section('content')
<style>
    body {
        background-color: #0f172a !important;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(124, 58, 237, 0.3);
    }
    
    .stats-card {
        background: #1e293b;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        transition: transform 0.3s;
        border-left: 5px solid;
        height: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.5);
    }
    
    .stats-card.purple { border-color: #8b5cf6; }
    .stats-card.blue { border-color: #3b82f6; }
    .stats-card.green { border-color: #10b981; }
    .stats-card.orange { border-color: #f59e0b; }
    
    .stat-number {
        font-size: 42px;
        font-weight: 700;
        margin: 10px 0;
        color: white;
    }
    
    .stat-label {
        color: #94a3b8;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 600;
    }
    
    .stat-subtitle {
        color: #64748b;
        font-size: 13px;
        margin-top: 5px;
    }
    
    .stat-icon {
        font-size: 45px;
        opacity: 0.2;
    }
    
    .content-card {
        background: #1e293b;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        margin-bottom: 30px;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .action-btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    .table-dark-custom {
        background-color: #0f172a;
        color: #e2e8f0;
    }
    
    .table-dark-custom thead {
        background-color: #1e293b;
        border-bottom: 2px solid #334155;
    }
    
    .table-dark-custom tbody tr {
        border-bottom: 1px solid #334155;
    }
    
    .table-dark-custom tbody tr:hover {
        background-color: #1e293b;
    }
</style>

<div class="container-fluid mt-4 mb-5">
    {{-- Header Section --}}
    <div class="dashboard-header">
        <h1 class="mb-2">
            <i class="fas fa-video"></i> Production Control Panel
        </h1>
        <p class="mb-0 opacity-75">Welcome, <strong>{{ Auth::user()->username }}</strong>! Manage your content production here.</p>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card purple">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Total Movies</div>
                        <div class="stat-number" style="color: #8b5cf6;">{{ number_format($totalMovies) }}</div>
                        <div class="stat-subtitle">Films in database</div>
                    </div>
                    <i class="fas fa-film stat-icon" style="color: #8b5cf6;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stats-card blue">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">TV Series</div>
                        <div class="stat-number" style="color: #3b82f6;">{{ number_format($totalTVSeries) }}</div>
                        <div class="stat-subtitle">Series in database</div>
                    </div>
                    <i class="fas fa-tv stat-icon" style="color: #3b82f6;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stats-card green">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Shows</div>
                        <div class="stat-number" style="color: #10b981;">{{ number_format($totalShows) }}</div>
                        <div class="stat-subtitle">Active shows</div>
                    </div>
                    <i class="fas fa-play-circle stat-icon" style="color: #10b981;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stats-card orange">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Episodes</div>
                        <div class="stat-number" style="color: #f59e0b;">{{ number_format($totalEpisodes) }}</div>
                        <div class="stat-subtitle">Total episodes</div>
                    </div>
                    <i class="fas fa-list-ol stat-icon" style="color: #f59e0b;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="content-card">
                <div class="card-title">
                    <i class="fas fa-bolt" style="color: #f59e0b;"></i>
                    <span>Quick Actions</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('production.movies.create') }}" class="action-btn btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Add New Movie
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('production.shows.create') }}" class="action-btn btn btn-success w-100">
                            <i class="fas fa-plus"></i> Add New Show
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('production.episodes.create') }}" class="action-btn btn btn-info w-100">
                            <i class="fas fa-plus"></i> Add New Episode
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('production.movies.index') }}" class="action-btn btn btn-secondary w-100">
                            <i class="fas fa-list"></i> View All Movies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Recent Movies --}}
        <div class="col-lg-6 mb-4">
            <div class="content-card">
                <div class="card-title">
                    <i class="fas fa-film" style="color: #8b5cf6;"></i>
                    <span>Recent Movies</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMovies as $movie)
                            <tr>
                                <td><code class="text-info">{{ $movie->tconst }}</code></td>
                                <td><strong>{{ Str::limit($movie->primaryTitle, 40) }}</strong></td>
                                <td><span class="badge bg-secondary">{{ $movie->startYear }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No recent movies</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('production.movies.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Movies <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Shows --}}
        <div class="col-lg-6 mb-4">
            <div class="content-card">
                <div class="card-title">
                    <i class="fas fa-play-circle" style="color: #10b981;"></i>
                    <span>Recent Shows</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Show Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentShows as $show)
                            <tr>
                                <td><code class="text-success">{{ $show->show_id }}</code></td>
                                <td><strong>{{ Str::limit($show->name, 40) }}</strong></td>
                                <td>
                                    <a href="{{ route('production.shows.edit', $show->show_id) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No recent shows</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('production.shows.index') }}" class="btn btn-sm btn-outline-success">
                        View All Shows <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Management Links --}}
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <div class="card-title">
                    <i class="fas fa-cog" style="color: #64748b;"></i>
                    <span>Content Management</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-grid">
                            <a href="{{ route('production.movies.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-film"></i> Manage Movies
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid">
                            <a href="{{ route('production.shows.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-play-circle"></i> Manage Shows
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid">
                            <a href="{{ route('production.episodes.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-list-ol"></i> Manage Episodes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
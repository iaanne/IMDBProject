@extends('layouts.app')

@section('title', 'Production Dashboard - CRUD')

@section('content')
<style>
    body {
        background-color: #0f172a !important;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
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
        border-left: 5px solid #a855f7;
        height: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.5);
    }
    
    .action-card {
        background: #1e293b;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        border: 2px solid transparent;
    }
    
    .action-card:hover {
        border-color: #a855f7;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(168, 85, 247, 0.3);
    }
    
    .action-card i {
        font-size: 48px;
        color: #a855f7;
        margin-bottom: 15px;
    }
    
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
</style>

<div class="container mt-5 mb-5">
    {{-- Header --}}
    <div class="dashboard-header">
        <h1 class="mb-2">
            <i class="fas fa-tools"></i> Production Dashboard
        </h1>
        <p class="mb-0 opacity-75">Welcome back, <strong>{{ Auth::user()->username }}</strong>! Manage your content here.</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Total Movies</div>
                        <div class="stat-number" style="color: #a855f7;">1,234</div>
                        <div class="stat-subtitle text-muted">In database</div>
                    </div>
                    <i class="fas fa-film" style="font-size: 45px; opacity: 0.2; color: #a855f7;"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Total TV Shows</div>
                        <div class="stat-number" style="color: #a855f7;">567</div>
                        <div class="stat-subtitle text-muted">In database</div>
                    </div>
                    <i class="fas fa-tv" style="font-size: 45px; opacity: 0.2; color: #a855f7;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-4">
        <div class="col-12 mb-3">
            <h3 class="text-white">
                <i class="fas fa-rocket"></i> Quick Actions
            </h3>
        </div>
        
        <div class="col-lg-4">
            <a href="{{ route('production.movies.index') }}" class="text-decoration-none">
                <div class="action-card">
                    <i class="fas fa-list"></i>
                    <h5 class="text-white mb-2">View All Movies</h5>
                    <p class="text-muted mb-0">Browse and manage movies</p>
                </div>
            </a>
        </div>
        
        <div class="col-lg-4">
            <a href="{{ route('production.movies.create') }}" class="text-decoration-none">
                <div class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    <h5 class="text-white mb-2">Add New Movie</h5>
                    <p class="text-muted mb-0">Create a new movie entry</p>
                </div>
            </a>
        </div>
        
        <div class="col-lg-4">
            <div class="action-card" style="opacity: 0.5; cursor: not-allowed;">
                <i class="fas fa-tv"></i>
                <h5 class="text-white mb-2">Manage TV Shows</h5>
                <p class="text-muted mb-0">Coming soon...</p>
            </div>
        </div>
    </div>
</div>
@endsection
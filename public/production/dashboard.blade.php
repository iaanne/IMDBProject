@extends('layouts.app')

@section('title', 'Production Dashboard - CRUD')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="text-white mb-4">
                <i class="fas fa-tools"></i> Production Dashboard
            </h1>
            <p class="text-white-50">Welcome, {{ Auth::user()->username }}! Manage your content here.</p>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Movies</h5>
                    <h2>{{ number_format($totalMovies) }}</h2>
                    <a href="{{ route('production.movies.index') }}" class="btn btn-light btn-sm mt-2">
                        Manage Movies
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total TV Shows</h5>
                    <h2>{{ number_format($totalShows) }}</h2>
                    <button class="btn btn-light btn-sm mt-2" disabled>
                        Manage Shows (Coming Soon)
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card bg-dark text-white">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-rocket"></i> Quick Actions</h4>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('production.movies.create') }}" class="btn btn-primary w-100 p-3">
                        <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                        Add New Movie
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('production.movies.index') }}" class="btn btn-info w-100 p-3">
                        <i class="fas fa-list fa-2x d-block mb-2"></i>
                        View All Movies
                    </a>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-secondary w-100 p-3" disabled>
                        <i class="fas fa-tv fa-2x d-block mb-2"></i>
                        Manage TV Shows
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
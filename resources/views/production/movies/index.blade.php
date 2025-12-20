@extends('layouts.app')

@section('title', 'Movies Management')

@section('content')
<style>
    body { background-color: #0f172a !important; }
    
    .page-header {
        background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
    }
    
    .content-card {
        background: #1e293b;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
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
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-1"><i class="fas fa-film"></i> Movies Management</h1>
                <p class="mb-0 opacity-75">Manage your movie collection</p>
            </div>
            <a href="{{ route('production.movies.create') }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus"></i> Add New Movie
            </a>
        </div>
    </div>

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

    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-dark-custom table-hover">
                <thead>
                    <tr>
                        <th width="100">ID</th>
                        <th>Title</th>
                        <th width="80" class="text-center">Year</th>
                        <th width="100" class="text-center">Rating</th>
                        <th width="100" class="text-center">Votes</th>
                        <th width="100" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies as $movie)
                    <tr>
                        <td><code class="text-info">{{ $movie->tconst }}</code></td>
                        <td>
                            <strong class="text-white">{{ $movie->primaryTitle }}</strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $movie->startYear }}</span>
                        </td>
                        <td class="text-center">
                            @if($movie->rating > 0)
                                <span class="badge bg-warning text-dark">
                                    {{ number_format($movie->rating, 1) }} ⭐
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($movie->votes > 0)
                                <span class="badge bg-info">
                                    {{ number_format($movie->votes) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('production.movies.destroy', $movie->tconst) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this movie?');"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-film fa-3x mb-3"></i>
                            <p>No movies found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
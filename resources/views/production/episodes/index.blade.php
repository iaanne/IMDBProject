@extends('layouts.app')

@section('title', 'Episodes Management')

@section('content')
<style>
    body { background-color: #0f172a !important; }
    .page-header {
        background: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
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
                <h1 class="mb-1"><i class="fas fa-list-ol"></i> Episodes Management</h1>
                <p class="mb-0 opacity-75">Manage TV show episodes</p>
            </div>
            <a href="{{ route('production.episodes.create') }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus"></i> Add New Episode
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-dark-custom table-hover">
                <thead>
                    <tr>
                        <th width="100">Episode ID</th>
                        <th>Episode Title</th>
                        <th>Series</th>
                        <th width="80" class="text-center">Season</th>
                        <th width="80" class="text-center">Episode</th>
                        <th width="100" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($episodes as $episode)
                    <tr>
                        <td><code class="text-info">{{ $episode->tconst }}</code></td>
                        <td>
                            <strong class="text-white">
                                {{ $episode->episode_title ?? 'Untitled' }}
                            </strong>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ Str::limit($episode->series_title ?? 'Unknown Series', 40) }}
                            </small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">S{{ $episode->seasonNumber }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">E{{ $episode->episodeNumber }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('production.episodes.edit', $episode->tconst) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-list-ol fa-3x mb-3"></i>
                            <p>No episodes found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
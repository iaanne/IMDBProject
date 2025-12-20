@extends('layouts.app')

@section('title', 'Add New Show')

@section('content')
<style>
    body { background-color: #0f172a !important; }
    .page-header {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }
    .form-card {
        background: #1e293b;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    .form-label { color: #e2e8f0; font-weight: 600; margin-bottom: 8px; }
    .form-control, .form-select {
        background-color: #0f172a;
        border: 1px solid #334155;
        color: #e2e8f0;
        padding: 12px;
        border-radius: 8px;
    }
    .form-control:focus {
        background-color: #0f172a;
        border-color: #10b981;
        color: #e2e8f0;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }
</style>

<div class="container mt-4 mb-5">
    <div class="page-header">
        <h1 class="mb-1"><i class="fas fa-plus-circle"></i> Add New Show</h1>
        <p class="mb-0 opacity-75">Create a new TV show entry</p>
    </div>

    <div class="form-card">
        <form action="{{ route('production.shows.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="show_id" class="form-label">
                    <i class="fas fa-key"></i> Show ID *
                </label>
                <input type="number" 
                       class="form-control @error('show_id') is-invalid @enderror" 
                       id="show_id" 
                       name="show_id" 
                       value="{{ old('show_id') }}"
                       required>
                @error('show_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">
                    <i class="fas fa-tv"></i> Show Name *
                </label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       maxlength="4000"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="overview" class="form-label">
                    <i class="fas fa-align-left"></i> Overview (Optional)
                </label>
                <textarea class="form-control @error('overview') is-invalid @enderror" 
                          id="overview" 
                          name="overview" 
                          rows="4"
                          maxlength="4000">{{ old('overview') }}</textarea>
                @error('overview')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Save Show
                </button>
                <a href="{{ route('production.shows.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

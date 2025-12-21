@extends('layouts.app')

@section('content')
<style>
    /* Mengikuti skema warna Showfy */
    :root {
        --c-rose: #d95f8c;
        --c-dark: #0d0d0d;
        --c-card: #141414;
    }

    .genre-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 30px;
        color: white;
        border-left: 5px solid var(--c-rose);
        padding-left: 20px;
    }

    .genre-btn {
        background: var(--c-card);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-decoration: none !important; /* Hapus garis biru */
        font-weight: 600;
        display: block;
        transition: all 0.3s ease;
        text-align: left;
    }

    .genre-btn:hover {
        background: linear-gradient(135deg, #870339, var(--c-rose));
        border-color: var(--c-rose);
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(217, 95, 140, 0.2);
    }
</style>

<div class="container py-5">
    <h1 class="genre-title">Genre</h1>
    
    <div class="row g-4">
        @foreach($genres as $g)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('genres.show', $g->genre_name) }}" class="genre-btn">
                    <i class="fas fa-tag me-2" style="color: var(--c-rose)"></i>
                    {{ $g->genre_name }}
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'HBO Production Dashboard')

@section('content')
<div class="container py-4">
    {{-- Header: BRANDING HBO --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary border-opacity-25 pb-3">
        <div>
            <h2 class="fw-bold text-white mb-0" style="letter-spacing: -1px;">
                <span style="font-family: sans-serif; letter-spacing: 1px; border: 2px solid white; padding: 0 6px; border-radius: 4px; font-size: 0.9em;">HBO</span> 
                <span class="text-white-50 fw-light mx-2">|</span> 
                Production Analytics
            </h2>
            <p class="text-white-50 small mb-0 mt-1">Internal Dashboard for HBO Original Content & Warehouse Insights.</p>
        </div>
        <button class="btn btn-gradient shadow-lg btn-sm px-4 rounded-pill">
            <i class="fas fa-file-export me-2"></i>Export Report
        </button>
    </div>

    {{-- SECTION 1: UPCOMING PIPELINE --}}
    <div class="mb-5">
        <div class="d-flex align-items-center mb-3">
            <h5 class="text-white fw-bold mb-0"><i class="fas fa-rocket me-2 text-rose"></i>Upcoming Pipeline</h5>
            <span class="badge bg-dark border border-secondary ms-2 text-white-50">{{ count($upcomingContent) }} Projects</span>
        </div>
        
        <div class="row row-cols-1 row-cols-md-5 g-3">
            @foreach($upcomingContent as $up)
            <div class="col">
                @php
                    $link = route('production.shows.edit', ['show_id' => $up->Id]);
                @endphp

                <a href="{{ $link }}" class="text-decoration-none">
                    <div class="card h-100 border-0 bg-dark-glass hover-scale">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge {{ $up->Type == 'Movie' ? 'bg-danger' : 'bg-primary' }} bg-opacity-75" style="font-size: 0.65rem;">
                                        {{ $up->Type }}
                                    </span>
                                    <small class="text-white-50" style="font-size: 0.7rem;">{{ $up->ReleaseYear ?? 'TBA' }}</small>
                                </div>
                                <h6 class="text-white fw-bold mb-1 text-truncate" title="{{ $up->Title }}" style="font-size: 0.95rem;">
                                    {{ $up->Title }}
                                </h6>
                            </div>
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                                <span class="text-white-50" style="font-size: 0.7rem;">Source</span>
                                <span class="text-rose fw-bold" style="font-size: 0.7rem;">{{ $up->Source }}</span>
                            </div>
                        </div>
                        <div class="progress" style="height: 3px;">
                            <div class="progress-bar bg-gradient" style="width: {{ rand(30, 90) }}%"></div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    {{-- SECTION 2: IN PRODUCTION --}}
    <div class="mb-5">
        <div class="d-flex align-items-center mb-3">
            <h5 class="text-white fw-bold mb-0">
                <span class="d-inline-block bg-danger rounded-circle me-2 blink-animation" style="width: 10px; height: 10px;"></span>
                On The Floor (In Production)
            </h5>
            <span class="badge bg-dark border border-secondary ms-2 text-white-50">{{ count($inProductionContent) }} Active</span>
        </div>

        <div class="row row-cols-1 row-cols-md-5 g-3">
            @foreach($inProductionContent as $prod)
            <div class="col">
                @php
                    $prodLink = route('production.shows.edit', ['show_id' => $prod->Id]);
                @endphp

                <a href="{{ $prodLink }}" class="text-decoration-none">
                    <div class="card h-100 border-0 bg-dark-glass hover-scale" style="border-left: 3px solid #dc3545 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-dark border border-secondary text-white-50" style="font-size: 0.65rem;">{{ $prod->Type }}</span>
                                <span class="text-danger small fw-bold" style="font-size: 0.7rem;">
                                    <i class="fas fa-video me-1"></i> Filming
                                </span>
                            </div>
                            <h6 class="text-white fw-bold mb-1 text-truncate" title="{{ $prod->Title }}">{{ $prod->Title }}</h6>
                            <p class="text-white-50 small mb-0" style="font-size: 0.75rem;">Est. Release: {{ $prod->ReleaseYear }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    {{-- SECTION 3: ANALYTICS GRID (SUDAH DIPERBAIKI - TIDAK ADA DUPLIKASI) --}}
    <div class="row g-4 mb-5">
        {{-- 1. HBO Market Leaders (KIRI - Lebar: 6) --}}
        <div class="col-lg-6">
            <div class="card h-100 border-0 bg-dark-glass">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3">
                    <h6 class="text-white fw-bold mb-0"><i class="fas fa-trophy me-2 text-warning"></i>HBO Market Leaders</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-black bg-opacity-25">
                                <tr class="text-white-50 small text-uppercase">
                                    <th class="ps-4">Title</th>
                                    <th class="text-center">Rating</th>
                                    <th class="text-end pe-4">Votes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProductions as $prod)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-white text-truncate" style="max-width: 200px;">{{ $prod->Title }}</div>
                                        <span class="badge bg-secondary bg-opacity-50 text-white-50" style="font-size: 0.65rem;">{{ $prod->Type }}</span>
                                    </td>
                                    <td class="text-center text-warning fw-bold"><i class="fas fa-star me-1"></i>{{ $prod->Rating }}</td>
                                    <td class="text-end pe-4 text-white-50">{{ number_format($prod->Votes) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Competitor Intelligence (KANAN - Lebar: 6) --}}
        <div class="col-lg-6">
            <div class="card h-100 border-0 bg-dark-glass">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3">
                    <h6 class="text-white fw-bold mb-0"><i class="fas fa-binoculars me-2 text-danger"></i>Competitor</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-black bg-opacity-25">
                                <tr class="text-white-50 small text-uppercase">
                                    <th class="ps-4">Production House</th>
                                    <th>Focus Genre</th>
                                    <th class="text-end pe-4">Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($competitorLeaderboard as $index => $comp)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold text-white-50 me-2 small">#{{ $index + 1 }}</span>
                                            <span class="text-white fw-bold">{{ $comp->Company }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark border border-secondary text-info">
                                            {{ $comp->TopGenre }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4" style="width: 30%;">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <span class="text-white fw-bold small">{{ $comp->ActiveProjects }}</span>
                                            <div class="progress bg-secondary bg-opacity-25" style="width: 60px; height: 4px;">
                                                <div class="progress-bar bg-danger" style="width: {{ ($comp->ActiveProjects / ($competitorLeaderboard[0]->ActiveProjects + 1)) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 4: TALENT SPOTLIGHT --}}
    <h5 class="text-white fw-bold mb-3"><i class="fas fa-star me-2 text-warning"></i>Talent Spotlight</h5>
    <div class="row g-4 mb-4">
        {{-- Directors --}}
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-dark-glass">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25">
                    <span class="text-white-50 small text-uppercase fw-bold">Top Directors</span>
                </div>
                <div class="card-body p-0">
                    @foreach($topDirectors as $index => $dir)
                    <div class="d-flex align-items-center p-3 border-bottom border-secondary border-opacity-10 hover-bg">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($dir->DirectorName) }}&background=random" 
                             class="rounded-circle me-3 tmdb-person-img" 
                             data-query="{{ $dir->DirectorName }}"
                             style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #333;">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="text-white mb-0 text-truncate">{{ $dir->DirectorName }}</h6>
                            <small class="text-white-50" style="font-size: 0.75rem;">{{ $dir->TotalWorks }} Credits</small>
                        </div>
                        <span class="badge bg-warning text-dark fw-bold ms-2">{{ $dir->AvgRating }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Writers --}}
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-dark-glass">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25">
                    <span class="text-white-50 small text-uppercase fw-bold">Top Writers</span>
                </div>
                <div class="card-body p-0">
                    @foreach($topWriters as $index => $write)
                    <div class="d-flex align-items-center p-3 border-bottom border-secondary border-opacity-10 hover-bg">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($write->WriterName) }}&background=random" 
                             class="rounded-circle me-3 tmdb-person-img" 
                             data-query="{{ $write->WriterName }}"
                             style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #333;">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="text-white mb-0 text-truncate">{{ $write->WriterName }}</h6>
                            <small class="text-rose" style="font-size: 0.75rem;">Top Rated</small>
                        </div>
                        <span class="badge bg-warning text-dark fw-bold ms-2">{{ $write->AvgRating }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Cast --}}
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-dark-glass">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25">
                    <span class="text-white-50 small text-uppercase fw-bold">Most Popular Cast</span>
                </div>
                <div class="card-body p-0">
                    @foreach($topCast as $index => $cast)
                    <div class="d-flex align-items-center p-3 border-bottom border-secondary border-opacity-10 hover-bg">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($cast->CastName) }}&background=random" 
                             class="rounded-circle me-3 tmdb-person-img" 
                             data-query="{{ $cast->CastName }}"
                             style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #333;">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="text-white mb-0 text-truncate">{{ $cast->CastName }}</h6>
                            <small class="text-white-50" style="font-size: 0.75rem;">Global Star</small>
                        </div>
                        <div class="text-end ms-2">
                            <div class="text-info fw-bold small">{{ number_format($cast->TotalVotes) }}</div>
                            <small class="text-white-50" style="font-size: 0.65rem;">Votes</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root { --c-rose: #d95f8c; }
    .bg-dark-glass { background: rgba(20, 20, 20, 0.95); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
    .text-rose { color: var(--c-rose) !important; }
    .bg-gradient { background: linear-gradient(135deg, #870339 0%, #d95f8c 100%) !important; }
    .hover-bg:hover { background-color: rgba(255,255,255,0.03); cursor: pointer; }
    .hover-scale { transition: transform 0.2s ease, border-color 0.2s ease; }
    .hover-scale:hover { transform: translateY(-3px); border-color: var(--c-rose) !important; }
    
    /* Animasi Kedip untuk "On The Floor" */
    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
    .blink-animation { animation: blink 2s infinite; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const apiKey = '8e8ed515442c24035b99b36d4bbb8e6d'; // <--- GANTI API KEY TMDB DISINI
        const baseUrl = 'https://image.tmdb.org/t/p/w200';

        if (apiKey !== '8e8ed515442c24035b99b36d4bbb8e6d') {
            const images = document.querySelectorAll('.tmdb-person-img');
            images.forEach(img => {
                const query = img.getAttribute('data-query');
                fetch(`https://api.themoviedb.org/3/search/person?api_key=${apiKey}&query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.results && data.results.length > 0) {
                            if (data.results[0].profile_path) {
                                img.src = `${baseUrl}${data.results[0].profile_path}`;
                            }
                        }
                    }).catch(console.error);
            });
        }
    });
</script>
@endsection
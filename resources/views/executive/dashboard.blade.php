@extends('layouts.app')

@section('title', 'Executive Dashboard - Analytics')

@section('content')
<style>
    body {
        background-color: #0f172a !important;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
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
    
    .stats-card.blue { border-color: #3b82f6; }
    .stats-card.green { border-color: #10b981; }
    .stats-card.orange { border-color: #f59e0b; }
    .stats-card.cyan { border-color: #06b6d4; }
    .stats-card.purple { border-color: #8b5cf6; }
    
    .chart-container {
        background: #1e293b;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        margin-bottom: 30px;
    }
    
    .chart-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
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
    
    .stat-subtitle {
        color: #64748b;
        font-size: 13px;
        margin-top: 5px;
    }
    
    .stat-icon {
        font-size: 45px;
        opacity: 0.2;
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
    {{-- Header --}}
    <div class="dashboard-header">
        <h1 class="mb-2">
            <i class="fas fa-chart-line"></i> Executive Analytics Dashboard
        </h1>
        <p class="mb-0 opacity-75">Welcome back, <strong>{{ Auth::user()->username }}</strong>! Here's your comprehensive analytics overview.</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card blue">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Top Movies</div>
                        <div class="stat-number" style="color: #3b82f6;">{{ $topMovies->count() }}</div>
                        <div class="stat-subtitle">Analyzed titles</div>
                    </div>
                    <i class="fas fa-film stat-icon" style="color: #3b82f6;"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card green">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Genres</div>
                        <div class="stat-number" style="color: #10b981;">{{ $genrePopularity->count() }}</div>
                        <div class="stat-subtitle">Total categories</div>
                    </div>
                    <i class="fas fa-list stat-icon" style="color: #10b981;"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card orange">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Top Actors</div>
                        <div class="stat-number" style="color: #f59e0b;">{{ $actorProductivity->take(10)->count() }}</div>
                        <div class="stat-subtitle">Most productive</div>
                    </div>
                    <i class="fas fa-users stat-icon" style="color: #f59e0b;"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card purple">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Top TV Shows</div>
                        <div class="stat-number" style="color: #8b5cf6;">{{ $topTVShows->count() }}</div>
                        <div class="stat-subtitle">Highest rated</div>
                    </div>
                    <i class="fas fa-tv stat-icon" style="color: #8b5cf6;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 1: Top Movies Bar Chart & Rating Trend Line Chart --}}
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-bar" style="color: #3b82f6;"></i>
                    <span>Top 10 Movies by Rating</span>
                </div>
                <canvas id="topMoviesChart" height="80"></canvas>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-trophy" style="color: #f59e0b;"></i>
                    <span>Top Rated Movies</span>
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead class="sticky-top">
                            <tr>
                                <th width="40">#</th>
                                <th>Title</th>
                                <th width="80">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topMovies->take(10) as $index => $movie)
                            <tr>
                                <td class="text-center">
                                    @if($index < 3)
                                        <span class="badge bg-warning text-dark fw-bold">{{ $index + 1 }}</span>
                                    @else
                                        <span class="text-muted">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-white">{{ Str::limit($movie->primaryTitle, 25) }}</strong>
                                    <br><small class="text-muted">{{ $movie->startYear }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark fw-bold">
                                        {{ number_format($movie->averageRating, 1) }} ⭐
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Rating Trend & Genre Popularity --}}
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-line" style="color: #06b6d4;"></i>
                    <span>Rating Trend Per Year</span>
                </div>
                <canvas id="ratingTrendChart" height="80"></canvas>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-pie" style="color: #10b981;"></i>
                    <span>Genre Popularity (Top 10)</span>
                </div>
                <canvas id="genreChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Row 3: Actor Productivity & Top TV Shows --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-user-tie" style="color: #f59e0b;"></i>
                    <span>Top 15 Most Productive Actors</span>
                </div>
                <canvas id="actorChart" height="100"></canvas>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-tv" style="color: #8b5cf6;"></i>
                    <span>Top 10 TV Shows</span>
                </div>
                <canvas id="tvShowsChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js Library --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Data dari Laravel
const topMovies = @json($topMovies);
const genrePopularity = @json($genrePopularity->take(10));
const actorProductivity = @json($actorProductivity->take(15));
const ratingTrend = @json($ratingTrend);
const topTVShows = @json($topTVShows);

// Color palette
const colors = {
    primary: '#3b82f6',
    success: '#10b981',
    warning: '#f59e0b',
    info: '#06b6d4',
    danger: '#ef4444',
    purple: '#8b5cf6'
};

// Chart default options
Chart.defaults.color = '#94a3b8';
Chart.defaults.borderColor = '#334155';

// 1. Top Movies Bar Chart
if (topMovies.length > 0) {
    const topMoviesCtx = document.getElementById('topMoviesChart').getContext('2d');
    new Chart(topMoviesCtx, {
        type: 'bar',
        data: {
            labels: topMovies.map(m => m.primaryTitle.length > 20 ? m.primaryTitle.substring(0, 20) + '...' : m.primaryTitle),
            datasets: [{
                label: 'Rating',  
                data: topMovies.map(m => parseFloat(m.averageRating)),
                backgroundColor: colors.primary,
                borderRadius: 8,
                barThickness: 35
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    callbacks: {
                        label: (context) => 'Rating: ' + context.parsed.y.toFixed(1) + ' ⭐'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    ticks: { color: '#94a3b8' },
                    grid: { color: '#334155' }
                },
                x: {
                    ticks: { color: '#94a3b8', maxRotation: 45, minRotation: 45 },
                    grid: { display: false }
                }
            }
        }
    });
}

// 2. Rating Trend Line Chart
if (ratingTrend.length > 0) {
    const ratingTrendCtx = document.getElementById('ratingTrendChart').getContext('2d');
    new Chart(ratingTrendCtx, {
        type: 'line',
        data: {
            labels: ratingTrend.map(r => r.startYear),
            datasets: [{
                label: 'Average Rating',
                data: ratingTrend.map(r => parseFloat(r.avg_rating)),
                borderColor: colors.info,
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 3,
                pointBackgroundColor: colors.info
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#e2e8f0' } },
                tooltip: { backgroundColor: '#1e293b' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    ticks: { color: '#94a3b8' },
                    grid: { color: '#334155' }
                },
                x: {
                    ticks: { color: '#94a3b8', maxRotation: 45 },
                    grid: { display: false }
                }
            }
        }
    });
}

// 3. Genre Pie Chart
if (genrePopularity.length > 0) {
    const genreCtx = document.getElementById('genreChart').getContext('2d');
    new Chart(genreCtx, {
        type: 'doughnut',
        data: {
            labels: genrePopularity.map(g => g.genre_name),
            datasets: [{
                data: genrePopularity.map(g => parseInt(g.total_titles)),
                backgroundColor: [
                    colors.primary, colors.success, colors.warning, colors.info, 
                    colors.danger, colors.purple, '#ec4899', '#f59e0b', '#10b981', '#3b82f6'
                ],
                borderWidth: 3,
                borderColor: '#1e293b'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#e2e8f0', padding: 10, font: { size: 11 } }
                },
                tooltip: { backgroundColor: '#1e293b' }
            }
        }
    });
}

// 4. Actor Productivity Horizontal Bar
if (actorProductivity.length > 0) {
    const actorCtx = document.getElementById('actorChart').getContext('2d');
    new Chart(actorCtx, {
        type: 'bar',
        data: {
            labels: actorProductivity.map(a => a.primaryName.length > 25 ? a.primaryName.substring(0, 25) + '...' : a.primaryName),
            datasets: [{
                label: 'Total Titles',
                data: actorProductivity.map(a => parseInt(a.total_titles)),
                backgroundColor: colors.warning,
                borderRadius: 8,
                barThickness: 25
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1e293b' }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { color: '#94a3b8' },
                    grid: { color: '#334155' }
                },
                y: {
                    ticks: { color: '#94a3b8', font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
}

// 5. Top TV Shows Bar Chart
if (topTVShows.length > 0) {
    const tvShowsCtx = document.getElementById('tvShowsChart').getContext('2d');
    new Chart(tvShowsCtx, {
        type: 'bar',
        data: {
            labels: topTVShows.map(tv => tv.name.length > 20 ? tv.name.substring(0, 20) + '...' : tv.name),
            datasets: [{
                label: 'Vote Average',
                data: topTVShows.map(tv => parseFloat(tv.vote_average)),
                backgroundColor: colors.purple,
                borderRadius: 8,
                barThickness: 30
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    callbacks: {
                        label: (context) => 'Rating: ' + context.parsed.x.toFixed(1) + ' ⭐'
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 10,
                    ticks: { color: '#94a3b8' },
                    grid: { color: '#334155' }
                },
                y: {
                    ticks: { color: '#94a3b8', font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
}
</script>
@endsection 
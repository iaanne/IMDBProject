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
    {{-- Header Section --}}
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
                        <div class="stat-label">Top TV Shows</div>
                        <div class="stat-number" style="color: #f59e0b;">{{ $topTVShows->count() }}</div>
                        <div class="stat-subtitle">Analyzed series</div>
                    </div>
                    <i class="fas fa-tv stat-icon" style="color: #f59e0b;"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card cyan">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Active Actors</div>
                        <div class="stat-number" style="color: #06b6d4;">{{ $actorProductivity->count() }}</div>
                        <div class="stat-subtitle">In database</div>
                    </div>
                    <i class="fas fa-users stat-icon" style="color: #06b6d4;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Chart 1: Top Movies Rating --}}
        <div class="col-lg-8 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-bar" style="color: #3b82f6;"></i>
                    <span>Top 10 Movies Rating Distribution</span>
                </div>
                <canvas id="ratingChart" height="100"></canvas>
            </div>
        </div>

        {{-- Chart 2: Rating Categories Pie --}}
        <div class="col-lg-4 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-pie" style="color: #10b981;"></i>
                    <span>Rating Categories</span>
                </div>
                <canvas id="ratingPieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Chart 3: Genre Popularity --}}
        <div class="col-lg-7 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-area" style="color: #f59e0b;"></i>
                    <span>Genre Popularity (Top 10)</span>
                </div>
                <canvas id="genreChart" height="120"></canvas>
            </div>
        </div>

        {{-- Table: Top Rated Movies --}}
        <div class="col-lg-5 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-trophy" style="color: #f59e0b;"></i>
                    <span>Top Rated Movies</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40" class="text-center">#</th>
                                <th>Title</th>
                                <th width="80" class="text-center">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topMovies->take(8) as $index => $movie)
                            <tr>
                                <td class="text-center">
                                    <span class="badge {{ $index < 3 ? 'bg-warning text-dark' : 'bg-secondary' }} fw-bold">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-white">{{ Str::limit($movie->primaryTitle, 30) }}</strong>
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

    <div class="row">
        {{-- Chart 4: Rating Trend Per Year --}}
        <div class="col-lg-8 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-line" style="color: #8b5cf6;"></i>
                    <span>Average Rating Trend by Year</span>
                </div>
                <canvas id="ratingTrendChart" height="80"></canvas>
            </div>
        </div>

        {{-- Table: Top TV Shows --}}
        <div class="col-lg-4 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-tv" style="color: #f59e0b;"></i>
                    <span>Top TV Shows</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40">#</th>
                                <th>Title</th>
                                <th width="70" class="text-center">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topTVShows->take(6) as $index => $show)
                            <tr>
                                <td>
                                    <span class="badge bg-info fw-bold">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <strong class="text-white">{{ Str::limit($show->primaryTitle, 25) }}</strong>
                                    <br><small class="text-muted">{{ $show->startYear }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fw-bold">{{ number_format($show->averageRating, 1) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Chart 5: Actor Productivity --}}
        <div class="col-lg-12 mb-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-user-friends" style="color: #06b6d4;"></i>
                    <span>Top 15 Most Productive Actors</span>
                </div>
                <canvas id="actorProductivityChart" height="70"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    const topMovies = @json($topMovies);
    const genrePopularity = @json($genrePopularity->take(10));
    const actorProductivity = @json($actorProductivity->take(15));
    const ratingTrend = @json($ratingTrend);
    const topTVShows = @json($topTVShows);
    
    const colors = {
        primary: '#3b82f6', 
        success: '#10b981', 
        warning: '#f59e0b',
        info: '#06b6d4', 
        danger: '#ef4444', 
        purple: '#8b5cf6'
    };

    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#334155';

    document.addEventListener("DOMContentLoaded", function() {
        if (topMovies.length > 0) {
            // Chart 1: Top Movies Rating Bar Chart
            new Chart(document.getElementById('ratingChart'), {
                type: 'bar',
                data: {
                    labels: topMovies.map(m => m.primaryTitle.length > 15 ? m.primaryTitle.substring(0, 15) + '...' : m.primaryTitle),
                    datasets: [{
                        label: 'Rating',
                        data: topMovies.map(m => parseFloat(m.averageRating)),
                        backgroundColor: colors.primary,
                        borderRadius: 8
                    }]
                },
                options: { 
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            max: 10 
                        } 
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });

            // Chart 2: Rating Categories Pie Chart
            const ratingCategories = {
                'Excellent (9+)': topMovies.filter(m => m.averageRating >= 9).length,
                'Great (8-9)': topMovies.filter(m => m.averageRating >= 8 && m.averageRating < 9).length,
                'Good (7-8)': topMovies.filter(m => m.averageRating >= 7 && m.averageRating < 8).length,
                'Average (<7)': topMovies.filter(m => m.averageRating < 7).length
            };
            new Chart(document.getElementById('ratingPieChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(ratingCategories),
                    datasets: [{
                        data: Object.values(ratingCategories),
                        backgroundColor: [colors.success, colors.primary, colors.warning, colors.danger],
                        borderWidth: 0
                    }]
                }
            });
        }

        // Chart 3: Genre Popularity Horizontal Bar
        if (genrePopularity.length > 0) {
            new Chart(document.getElementById('genreChart'), {
                type: 'bar',
                data: {
                    labels: genrePopularity.map(g => g.genre_name),
                    datasets: [{
                        label: 'Total Titles',
                        data: genrePopularity.map(g => parseInt(g.total_titles)),
                        backgroundColor: colors.warning,
                        borderRadius: 5
                    }]
                },
                options: { 
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: true }
                    }
                }
            });
        }

        // Chart 4: Rating Trend Per Year Line Chart
        if (ratingTrend.length > 0) {
            new Chart(document.getElementById('ratingTrendChart'), {
                type: 'line',
                data: {
                    labels: ratingTrend.map(t => t.release_year),
                    datasets: [{
                        label: 'Average Rating',
                        data: ratingTrend.map(t => parseFloat(t.avg_rating)),
                        borderColor: colors.purple,
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10
                        }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });
        }

        // Chart 5: Actor Productivity Bar Chart
        if (actorProductivity.length > 0) {
            new Chart(document.getElementById('actorProductivityChart'), {
                type: 'bar',
                data: {
                    labels: actorProductivity.map(a => a.primaryName),
                    datasets: [{
                        label: 'Total Titles',
                        data: actorProductivity.map(a => parseInt(a.total_titles)),
                        backgroundColor: colors.info,
                        borderRadius: 6
                    }]
                },
                options: {
                    plugins: {
                        legend: { display: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
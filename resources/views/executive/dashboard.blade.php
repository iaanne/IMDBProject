@extends('layouts.app')

@section('title', 'Executive Analytics - Showfy')

@section('content')
<style>
    /* === 1. DEFINISI WARNA (PALETTE) === */
    :root {
        --c-rose: #d95f8c;
        --c-amaranth: #870339;
        --c-onyx: #0d0d0d;
        --c-gold: #fbbf24;
        --text-muted: #a3a3a3;
        --text-white: #ffffff; /* Tambahkan variabel untuk warna putih */
    }

    /* === 2. DASHBOARD STYLES === */
    .dashboard-header {
        background: linear-gradient(135deg, var(--c-amaranth) 0%, var(--c-rose) 100%);
        color: white; 
        padding: 40px 30px; 
        border-radius: 20px; 
        margin-bottom: 40px;
        box-shadow: 0 20px 50px rgba(135, 3, 57, 0.3); 
        position: relative; 
        overflow: hidden; 
        border: 1px solid rgba(255,255,255,0.1);
    }
    .stats-card {
        background: #141414; 
        border-radius: 16px; 
        padding: 25px; 
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        height: 100%; 
        position: relative; 
        overflow: hidden;
    }
    .stats-card:hover { 
        transform: translateY(-5px); 
        border-color: var(--c-rose); 
        box-shadow: 0 10px 30px rgba(217, 95, 140, 0.15); 
    }
    .stats-card::after { 
        content: ''; 
        position: absolute; 
        left: 0; 
        top: 0; 
        bottom: 0; 
        width: 4px; 
        background: var(--c-rose); 
        opacity: 0.5; 
    }
    
    .stat-label { 
        color: var(--text-muted); 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 1.5px; 
        font-weight: 600; 
        margin-bottom: 5px; 
    }
    .stat-number { 
        font-size: 2.5rem; 
        font-weight: 800; 
        color: white; 
        line-height: 1.2; 
        margin-bottom: 5px; 
        background: linear-gradient(90deg, #fff, #ffcce0); 
        -webkit-background-clip: text; 
        -webkit-text-fill-color: transparent; 
    }
    .stat-icon { 
        font-size: 3rem; 
        color: var(--c-rose); 
        opacity: 0.15; 
        position: absolute; 
        right: 20px; 
        top: 50%; 
        transform: translateY(-50%); 
    }

    /* Chart Containers & Wrappers */
    .chart-container {
        background: #141414; 
        border-radius: 16px; 
        padding: 20px; 
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0,0,0,0.2); 
        height: 100%; 
        display: flex; 
        flex-direction: column; 
        justify-content: center;
    }
    .chart-title { 
        font-size: 1rem; 
        font-weight: 700; 
        margin-bottom: 15px; 
        color: white; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        flex-shrink: 0; 
    }
    .chart-title i { 
        color: var(--c-rose); 
        background: rgba(217, 95, 140, 0.1); 
        padding: 8px; 
        border-radius: 8px; 
    }
    
    .chart-wrapper { 
        position: relative; 
        width: 100%; 
        height: 300px; 
        margin-top: auto; 
        margin-bottom: auto; 
    }

    /* Table Styles */
    .table-custom { 
        width: 100%; 
        border-collapse: separate; 
        border-spacing: 0 8px; 
    }
    .table-custom thead th { 
        color: var(--text-white) !important; /* Ubah ke putih */
        font-size: 0.8rem; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        padding: 0 15px 10px 15px; 
        border: none; 
    }
    .table-custom tbody tr { 
        background: rgba(255, 255, 255, 0.03); 
        transition: 0.2s; 
    }
    .table-custom tbody tr:hover { 
        background: rgba(217, 95, 140, 0.1); 
        transform: scale(1.01); 
    }
    .table-custom td { 
        padding: 15px; 
        border: none; 
        vertical-align: middle; 
        color: white !important; /* Pastikan warna putih */
    }
    .table-custom td:first-child { 
        border-radius: 10px 0 0 10px; 
    }
    .table-custom td:last-child { 
        border-radius: 0 10px 10px 0; 
    }

    .table-wrapper { 
        max-height: 420px; 
        overflow-y: auto; 
        padding-right: 5px; 
    }
    .table-wrapper::-webkit-scrollbar { 
        width: 4px; 
    }
    .table-wrapper::-webkit-scrollbar-thumb { 
        background: #333; 
        border-radius: 2px; 
    }

    .movie-link { 
        text-decoration: none; 
        color: white !important; /* Pastikan warna putih */
        transition: 0.3s; 
    }
    .movie-link:hover { 
        color: var(--c-rose) !important; /* Warna pink saat hover */
    }
    
    /* Perbaikan untuk teks di badge */
    .badge {
        color: white !important;
    }
</style>

<div class="container-fluid py-4">
    
    {{-- HEADER --}}
    <div class="dashboard-header">
        <div class="position-relative z-1">
            <h1 class="fw-bold mb-2"><i class="fas fa-chart-pie me-2"></i> Executive Overview</h1>
            <p class="mb-0 opacity-75 fs-5">Laporan performa konten Showfy secara real-time.</p>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stat-label">Total Movies</div>
                <div class="stat-number">{{ count($topMovies) > 0 ? '10+' : '0' }}</div>
                <div class="small text-white-muted"><i class="fas fa-arrow-up text-success me-1"></i> Data Teranalisis</div>
                <i class="fas fa-film stat-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stat-label">TV Shows</div>
                <div class="stat-number">{{ count($topTVShows) > 0 ? '10+' : '0' }}</div>
                <div class="small text-white-muted"><i class="fas fa-star text-warning me-1"></i> Top Rated</div>
                <i class="fas fa-tv stat-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stat-label">Genre Variances</div>
                <div class="stat-number">{{ count($genrePopularity) }}</div>
                <div class="small text-white-muted">Kategori Konten</div>
                <i class="fas fa-layer-group stat-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stat-label">Top Talents</div>
                <div class="stat-number">{{ count($actorProductivity) }}</div>
                <div class="small text-white-muted">Aktor Paling Aktif</div>
                <i class="fas fa-user-astronaut stat-icon"></i>
            </div>
        </div>
    </div>

    {{-- ROW 2: MAIN CHART & TABLE --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-wave-square"></i> Tren Kualitas Film (Rating per Tahun)</div>
                <div class="chart-wrapper">
                    <canvas id="ratingTrendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- TABEL FILM --}}
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-crown text-warning"></i> Film Terbaik (Top 10)</div>
                <div class="table-wrapper">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Film</th>
                                <th class="text-end">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topMovies as $index => $movie)
                            <tr>
                                <td class="fw-bold text-white">{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        // 1. Ambil ID mentah
                                        $rawId = $movie->tconst ?? $movie->show_id ?? null;
                                        $title = $movie->primaryTitle ?? $movie->name ?? 'Unknown Title';
                                        
                                        // 2. Format ID agar selalu tt + 7 digit (Mencegah 404)
                                        $movieId = null;
                                        if ($rawId) {
                                            $cleanId = preg_replace('/[^0-9]/', '', $rawId);
                                            $movieId = 'tt' . str_pad($cleanId, 7, '0', STR_PAD_LEFT);
                                        }
                                    @endphp

                                    @if($movieId)
                                        {{-- Pastikan ini mengirim tconst --}}
<a href="{{ route('titles.show', ['tconst' => $movieId]) }}" class="fw-bold movie-link">
    {{ Str::limit($title, 22) }}
</a>
                                    @else
                                        <span class="fw-bold text-white">{{ Str::limit($title, 22) }}</span>
                                    @endif
                                    
                                    <div class="small text-white">{{ $movie->startYear ?? '-' }}</div>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-dark border border-warning text-warning rounded-pill px-3">
                                        <i class="fas fa-star me-1 small"></i> {{ number_format($movie->averageRating ?? 0, 1) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-white">Data tidak tersedia</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: DETAIL CHARTS --}}
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-users"></i> Produktivitas Aktor</div>
                <div class="chart-wrapper">
                    <canvas id="actorChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-pie"></i> Distribusi Genre</div>
                <div class="chart-wrapper">
                    <canvas id="genreChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-tv"></i> Top TV Series <small class="ms-2 text-white fw-normal fs-6">(Klik Grafik)</small></div>
                <div class="chart-wrapper">
                    <canvas id="tvShowsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const topMovies = @json($topMovies);
    const genrePopularity = @json($genrePopularity);
    const actorProductivity = @json($actorProductivity);
    const ratingTrend = @json($ratingTrend);
    const topTVShows = @json($topTVShows); 

    const theme = {
        rose: '#d95f8c', 
        amaranth: '#870339', 
        text: '#ffffff', // Ubah ke putih
        grid: '#333333',
        palette: ['#65022a', '#870339', '#aa4465', '#ce306fff', '#d95f8c', '#f895bcff', '#fbbf24', '#ffd875ff']
    };
    
    // Set default warna teks ke putih
    Chart.defaults.color = theme.text;
    Chart.defaults.borderColor = theme.grid;
    Chart.defaults.font.family = "'Outfit', sans-serif";

    // A. TREND CHART
    if(ratingTrend.length > 0) {
        const recentTrend = ratingTrend.filter(r => parseInt(r.startYear) >= 2000); 
        new Chart(document.getElementById('ratingTrendChart'), {
            type: 'line',
            data: {
                labels: recentTrend.map(r => r.startYear),
                datasets: [{
                    label: 'Rata-rata Rating',
                    data: recentTrend.map(r => r.avg_rating),
                    borderColor: theme.rose,
                    backgroundColor: (context) => {
                        const ctx = context.chart.ctx;
                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(217, 95, 140, 0.5)');
                        gradient.addColorStop(1, 'rgba(217, 95, 140, 0)');
                        return gradient;
                    },
                    fill: true, 
                    tension: 0.4, 
                    borderWidth: 3, 
                    pointRadius: 0, 
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false } 
                },
                scales: { 
                    y: { 
                        beginAtZero: false, 
                        min: 5, 
                        max: 10,
                        ticks: { color: theme.text } // Pastikan teks sumbu Y putih
                    }, 
                    x: { 
                        grid: { display: false },
                        ticks: { color: theme.text } // Pastikan teks sumbu X putih
                    } 
                }
            }
        });
    }

    // B. ACTOR CHART
    if(actorProductivity.length > 0) {
        new Chart(document.getElementById('actorChart'), {
            type: 'bar',
            data: {
                labels: actorProductivity.slice(0, 10).map(a => a.primaryName.substring(0, 15)), 
                datasets: [{
                    label: 'Jumlah Judul',
                    data: actorProductivity.slice(0, 10).map(a => a.total_titles),
                    backgroundColor: theme.amaranth, 
                    borderRadius: 4, 
                    barThickness: 15
                }]
            },
            options: {
                indexAxis: 'y', 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false } 
                },
                scales: { 
                    x: { 
                        display: false,
                        ticks: { color: theme.text } // Pastikan teks sumbu X putih
                    }, 
                    y: { 
                        grid: { display: false }, 
                        ticks: { color: theme.text } // Pastikan teks sumbu Y putih
                    } 
                }
            }
        });
    }

    // C. GENRE CHART
    if(genrePopularity.length > 0) {
        new Chart(document.getElementById('genreChart'), {
            type: 'doughnut',
            data: {
                labels: genrePopularity.slice(0, 8).map(g => g.genre_name),
                datasets: [{
                    data: genrePopularity.slice(0, 8).map(g => g.total_titles),
                    backgroundColor: theme.palette, 
                    borderWidth: 0, 
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            color: theme.text, // Pastikan teks legenda putih
                            boxWidth: 10, 
                            usePointStyle: true, 
                            padding: 20 
                        } 
                    } 
                },
                cutout: '70%'
            }
        });
    }

   // ... kode chart sebelumnya ...

// D. TV SHOWS CHART
if(topTVShows.length > 0) {
    const tvChart = new Chart(document.getElementById('tvShowsChart'), {
        type: 'bar',
        data: {
            // ... (bagian data sama seperti sebelumnya)
            labels: topTVShows.slice(0, 8).map(t => t.name ? t.name.substring(0, 10) + '..' : 'Unknown'),
            datasets: [{
                label: 'Rating',
                data: topTVShows.slice(0, 8).map(t => t.vote_average),
                backgroundColor: theme.rose, 
                borderRadius: 6, 
                barThickness: 20
            }]
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false } 
            },
            scales: { 
                y: { 
                    beginAtZero: true, 
                    max: 10, 
                    grid: { color: '#333' },
                    ticks: { color: theme.text } // Pastikan teks sumbu Y putih
                }, 
                x: { 
                    grid: { display: false },
                    ticks: { color: theme.text } // Pastikan teks sumbu X putih
                } 
            },
            
            // === BAGIAN YANG DIBERIKAN ===
            onClick: (e) => {
                const points = tvChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

                if (points.length) {
                    const firstPoint = points[0];
                    const dataIndex = firstPoint.index;
                    const show = topTVShows[dataIndex];
                    
                    // Ambil ID dari data (bisa id angka atau tconst)
                    let rawId = show.tconst || show.show_id || show.id;

                    if(rawId) { 
                        // 1. Ubah jadi String & Hapus huruf (jaga-jaga kalau datanya kotor)
                        let cleanId = String(rawId).replace(/\D/g, '');
                        
                        // 2. Tambahkan 'tt' dan 0 di depan (Padding) agar jadi 7 digit angka
                        // Contoh: 246 -> tt0000246
                        let finalId = 'tt' + cleanId.padStart(7, '0');
                        
                        // 3. Redirect ke URL yang benar
                        // PERHATIKAN: Saya ubah '/titles/' jadi '/title/' sesuai route kamu
                        window.location.href = "/title/" + finalId;
                    }
                }
            },
            onHover: (event, chartElement) => {
                event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            }
        }
    });
}

onClick: (e) => {
    const points = tvChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

    if (points.length) {
        const index = points[0].index;
        const show = topTVShows[index];
        let rawId = show.tconst || show.show_id || show.id;

        if(rawId) { 
            let finalId = String(rawId);
            
            // Jika ID belum punya 'tt', baru kita tambahkan & padding
            if (!finalId.startsWith('tt')) {
                let cleanNumber = finalId.replace(/\D/g, '');
                finalId = 'tt' + cleanNumber.padStart(7, '0');
            }
            
            // Redirect sesuai route yang kita rapikan tadi
            window.location.href = "/title/" + finalId;
        }
    }
}
</script>

@endsection
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>YANKAI FLIX</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --netflix-red: #e50914;
            --netflix-black: #141414;
            --netflix-gray: #181818;
            --text-light: #f5f5f1;
            --text-gray: #b3b3b3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            background-color: var(--netflix-black);
            color: var(--text-light);
        }

        /* Header Nav */
        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background: linear-gradient(to bottom, rgba(20, 20, 20, 0.9) 0%, rgba(20, 20, 20, 0) 100%);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: background-color 0.5s ease;
        }
    </style>
</head>

<body>

    {{-- Nav Header --}}

    <header class="main-nav" id="mainNav">
        <div class="nav-left">
            <a href="#" class="logo">YANKAI FLIX</a>
            <a href="#" class="nav-link">Beranda</a>
            <a href="#" class="nav-link">Serial TV</a>
            <a href="#" class="nav-link">Film</a>
            <a href="#" class="nav-link">My List</a>
        </div>
        <div class="nav-right">
            <a href="#" class="nav-link"><i class="fas fa-search"></i></a>
            <a href="#" class="nav-link"><i class="fas fa-search"></i></a>
            <img src="" alt="">
        </div>
    </header>

    <main>
        {{-- Hero Banner --}}
        @if (isset($featuredMovie))
            <section class="hero-banner" style="background-image: url('https://source.unsplash.com/1920x1080/?{{ urlencode($featuredMovie->primaryTitle) }}');">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1 class="hero-title">{{ $featuredMovie->primaryTitle }}</h1>
                    <div class="hero-meta">
                        <span class="match">98% Cocok</span>
                        <span class="rating">{{ $featuredMovie->startYear }}</span>
                        <span class="meta-info">{{ $featuredMovie->runtimeMinutes }} menit</span>
                    </div>
                    <p>{{ Str::limit($featuredMovie->genres, 50) }}</p>
                    <div class="hero-buttons">
                        <button class="btn btn-play"><i class="fas fa-play"></i> Putar</button>
                        <button class="btn btn-info"><i class="fas fa-info-circle"></i> Info Selengkapnya</button>
                    </div>
                </div>
            </section>
        @endif


        {{-- Trending Now --}}
        @if ($trendingMovies->count() > 0)
            <section class="carousel-section">
                <h2 class="carousel-title">Sedang Trending Sekarang</h2>
                <div class="carousel-container">
                    <button class="carousel-arrow left" onclick="scrollCarousel(this, -1)"><i
                            class="fas fa-chevron-left"></i></button>
                    <div class="carousel-row">
                        @forelse ($trendingMovies as $movie)
                            <div class="movie-card">
                                <img src="https://source.unsplash.com/250x140/?{{ urlencode($movie->primaryTitle) }}"
                                    alt="{{ $movie->primaryTitle }}">
                            </div>
                        @empty
                            <p>Tidak ada film.</p>
                        @endforelse
                    </div>
                    <button class="carousel-arrow right" onclick="scrollCarousel(this, 1)"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </section>
        @endif

        {{-- New Releases --}}

        @if ($newReleases->count() > 0)
            <section class="carousel-section">
                <h2 class="carousel-title">Rilis Terbaru</h2>
                <div class="carousel-container">
                    <button class="carousel-arrow left" onclick="scrollCarousel(this, -1)"><i
                            class="fas fa-chevron-left"></i></button>
                    <div class="carousel-row">
                        @forelse ($newReleases as $movie)
                            <div class="movie-card">
                                <img src="https://source.unsplash.com/250x140/?{{ urlencode($movie->primaryTitle) }}"
                                    alt="{{ $movie->primaryTitle }}">
                            </div>
                        @empty
                            <p>Tidak ada film.</p>
                        @endforelse
                    </div>
                    <button class="carousel-arrow right" onclick="scrollCarousel(this, 1)"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </section>
        @endif

        {{-- Top Romance --}}

        @if ($topRomanceMovies->count() > 0)
            <section class="carousel-section">
                <h2 class="carousel-title">Film Komedi Teratas</h2>
                <div class="carousel-container">
                    <button class="carousel-arrow left" onclick="scrollCarousel(this, -1)"><i
                            class="fas fa-chevron-left"></i></button>
                    <div class="carousel-row portrait">
                        @forelse ($topRomanceMovies as $movie)
                            <div class="movie-card">
                                <img src="https://source.unsplash.com/150x225/?{{ urlencode($movie->primaryTitle) }}"
                                    alt="{{ $movie->primaryTitle }}">
                            </div>
                        @empty
                            <p>Tidak ada film.</p>
                        @endforelse
                    </div>
                    <button class="carousel-arrow right" onclick="scrollCarousel(this, 1)"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </section>
        @endif


        {{-- Comedy Movies --}}

        @if ($actionMovies->count() > 0)
            <section class="carousel-section">
                <h2 class="carousel-title">Film Aksi</h2>
                <div class="carousel-container">
                    <button class="carousel-arrow left" onclick="scrollCarousel(this, -1)"><i
                            class="fas fa-chevron-left"></i></button>
                    <div class="carousel-row">
                        @forelse ($actionMovies as $movie)
                            <div class="movie-card">
                                <img src="https://source.unsplash.com/250x140/?{{ urlencode($movie->primaryTitle) }}"
                                    alt="{{ $movie->primaryTitle }}">
                            </div>
                        @empty
                            <p>Tidak ada film.</p>
                        @endforelse
                    </div>
                    <button class="carousel-arrow right" onclick="scrollCarousel(this, 1)"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </section>
        @endif

    </main>

    <script>
        function scrollCarousel(button, direction) {
            const container = button.parentElement.querySelector('.carousel-row');
            const scrollAmount = 50;
            container.scrollBy({
                left: scrollAmount * direction,
                behavior: 'smooth'
            });
        }

        windows.addEventListener('scrool', () => {
            const nav = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>

</body>

</html>

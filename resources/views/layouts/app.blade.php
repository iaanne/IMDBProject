<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IMDB Clone')</title>
    
    <!-- Bootstrap 5 CSS (Gunakan CDN dari Cloudflare atau jsDelivr tanpa tracking) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
          crossorigin="anonymous">
    
    <!-- Font Awesome (Gunakan kit atau CDN tanpa tracking) -->
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
          crossorigin="anonymous" 
          referrerpolicy="no-referrer" />
    
    <!-- Atau alternatif: Gunakan Bootstrap dari Cloudflare -->
    <!--
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    -->
    
    <!-- Tambahkan meta tag untuk memperbaiki tracking prevention -->
    <meta http-equiv="Permissions-Policy" content="interest-cohort=()">
    
    <style>
        /* Pindahkan semua CSS inline ke sini */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand {
            font-weight: bold;
            color: #f5c518 !important;
        }
        .movie-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .rating-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .genre-badge {
            background-color: #6c757d;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }
        footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #343a40;
            color: white;
        }
        #loadingOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            z-index: 9999;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-film me-2"></i>IMDB Clone
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('titles.search') }}">Search</a>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex" action="{{ route('titles.search') }}" method="GET" id="navSearchForm">
                    <div class="input-group">
                        <input type="text" 
                               name="q" 
                               class="form-control" 
                               placeholder="Cari film/series..." 
                               value="{{ request('q') ?? '' }}"
                               style="width: 250px;">
                        <button class="btn btn-warning" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- Loading Overlay -->
    <div id="loadingOverlay">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 fs-5">Loading...</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} IMDB Clone - Database Project</p>
            <p class="text-muted">Data dari IMDB dataset</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle dengan Popper (Tanpa tracking) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
            crossorigin="anonymous"></script>
    
    <!-- jQuery (Opsional, untuk handling form yang lebih mudah) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" 
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" 
            crossorigin="anonymous"></script>
    
    @yield('scripts')
    
    <script>
    // JavaScript untuk handling loading state
    $(document).ready(function() {
        // Show loading on form submit
        $('#navSearchForm').on('submit', function() {
            $('#loadingOverlay').fadeIn(300);
        });
        
        // Hide loading when page is fully loaded
        $(window).on('load', function() {
            $('#loadingOverlay').fadeOut(300);
        });
        
        // Auto-hide after 5 seconds (fallback)
        setTimeout(function() {
            $('#loadingOverlay').fadeOut(300);
        }, 5000);
        
        // Handle Escape key to clear search
        $('input[name="q"]').on('keyup', function(e) {
            if (e.key === 'Escape') {
                $(this).val('');
            }
        });
    });
    
    // Fallback jika jQuery tidak load
    document.addEventListener('DOMContentLoaded', function() {
        // Manual form submit handler
        var forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            form.addEventListener('submit', function() {
                document.getElementById('loadingOverlay').style.display = 'block';
            });
        });
    });
    </script>
</body>
</html>
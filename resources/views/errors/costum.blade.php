<!DOCTYPE html>
<html>
<head>
    <title>Error - IMDB Clone</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #f8f9fa; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .error-code { color: #dc3545; font-size: 48px; font-weight: bold; }
        .error-message { color: #333; font-size: 24px; margin: 20px 0; }
        .debug-info { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; font-family: monospace; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">500</div>
        <div class="error-message">Server Error</div>
        
        <p>Terjadi kesalahan saat memuat halaman.</p>
        
        @if(isset($error))
        <div class="debug-info">
            <strong>Error Message:</strong><br>
            {{ $error }}
            
            @if(isset($tconst))
            <br><br>
            <strong>Title ID:</strong> {{ $tconst }}
            @endif
        </div>
        @endif
        
        <div style="margin-top: 30px;">
            <a href="{{ url('/') }}" class="btn">← Kembali ke Home</a>
            <a href="{{ url('/search') }}" class="btn">Cari Film Lain</a>
            
            @if(isset($tconst))
            <a href="https://www.imdb.com/title/{{ $tconst }}/" 
               target="_blank" 
               class="btn" 
               style="background: #f5c518; color: #000;">
                Lihat di IMDb
            </a>
            @endif
        </div>
        
        @if(config('app.debug') && isset($trace))
        <div class="debug-info" style="margin-top: 20px;">
            <strong>Debug Trace:</strong><br>
            <pre style="white-space: pre-wrap; font-size: 12px;">{{ $trace }}</pre>
        </div>
        @endif
    </div>
</body>
</html>
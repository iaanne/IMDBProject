<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TitleController extends Controller
{
    /**
     * HOMEPAGE - Tampilan awal dengan rekomendasi/populer
     */
    public function home()
    {
        try {
            Log::info("Loading homepage...");
            
            // Ambil 20 film/series terbaru untuk homepage
            $latestTitles = DB::select('
                SELECT TOP 20 
                    tconst, 
                    COALESCE(primaryTitle, \'Untitled\') as primaryTitle, 
                    COALESCE(titleType, \'Unknown\') as titleType, 
                    startYear, 
                    runtimeMinutes
                FROM dim_title 
                WHERE startYear IS NOT NULL 
                ORDER BY startYear DESC
            ');

            // Ambil top 10 rating tertinggi
            $topRated = DB::select('
                SELECT TOP 10
                    dt.tconst,
                    COALESCE(dt.primaryTitle, \'Untitled\') as primaryTitle,
                    fr.averageRating,
                    fr.numVotes
                FROM fact_title_rating fr
                JOIN dim_title dt ON fr.tconst = dt.tconst
                WHERE fr.numVotes > 1000
                ORDER BY fr.averageRating DESC, fr.numVotes DESC
            ');

            // Hitung statistik dengan error handling
            $stats = null;
            try {
                $statsResult = DB::select('
                    SELECT 
                        (SELECT COUNT(*) FROM dim_title WHERE primaryTitle IS NOT NULL) as total_titles,
                        (SELECT COUNT(DISTINCT nconst) FROM dim_person) as total_people,
                        (SELECT COUNT(DISTINCT genre_id) FROM bridge_title_genre) as total_genres
                ');
                
                if (!empty($statsResult)) {
                    $stats = $statsResult[0];
                }
            } catch (\Exception $statsError) {
                Log::warning("Stats query failed: " . $statsError->getMessage());
                $stats = (object)[
                    'total_titles' => 0,
                    'total_people' => 0,
                    'total_genres' => 0
                ];
            }
            
            // Fallback jika stats masih null
            if (!$stats) {
                $stats = (object)[
                    'total_titles' => count($latestTitles),
                    'total_people' => 0,
                    'total_genres' => 0
                ];
            }

            Log::info("Homepage data loaded successfully");
            return view('home', compact('latestTitles', 'topRated', 'stats'));
            
        } catch (\Exception $e) {
            Log::error("Homepage error: " . $e->getMessage());
            
            // Return dengan data kosong jika error
            return view('home', [
                'latestTitles' => [],
                'topRated' => [],
                'stats' => (object)[
                    'total_titles' => 0,
                    'total_people' => 0,
                    'total_genres' => 0
                ],
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * SEARCH - Pencarian judul berdasarkan keyword
     */
    public function search(Request $request)
    {
        $keyword = trim($request->input('q', ''));
        $results = [];
        $error = null;
        $executionTime = 0;
        
        if (!empty($keyword)) {
            $startTime = microtime(true);
            
            try {
                Log::info("Search started for: '{$keyword}'");
                
                // Gunakan Query Builder dengan optimasi
                try {
                    Log::info("Trying optimized Query Builder...");
                    
                    $results = DB::table('dim_title')
                        ->select('tconst', 'primaryTitle', 'titleType', 'startYear', 'runtimeMinutes')
                        ->where('primaryTitle', 'like', '%' . $keyword . '%')
                        ->orderBy('startYear', 'desc')
                        ->take(30) // Limit lebih kecil
                        ->get()
                        ->toArray();
                    
                    Log::info("Query Builder success: " . count($results) . " results");
                    
                } catch (\Exception $qbError) {
                    Log::warning("Query Builder failed: " . $qbError->getMessage());
                    $error = "Sistem pencarian sedang mengalami masalah. Silakan coba lagi.";
                }
                
                $executionTime = round(microtime(true) - $startTime, 3);
                Log::info("Search completed in {$executionTime} seconds");
                
            } catch (\Exception $e) {
                $executionTime = round(microtime(true) - $startTime, 3);
                Log::error("Search completely failed after {$executionTime}s: " . $e->getMessage());
                $error = "Pencarian membutuhkan waktu terlalu lama. Coba kata kunci yang lebih spesifik.";
            }
        }
        
        // Jika results kosong tapi ada keyword, beri pesan
        if (!empty($keyword) && empty($results) && !$error) {
            $error = "Tidak ditemukan hasil untuk '{$keyword}'. Coba kata kunci lain.";
        }
        
        return view('titles.search', [
            'results' => $results,
            'keyword' => $keyword,
            'error' => $error,
            'executionTime' => $executionTime
        ]);
    }

    /**
     * SHOW DETAIL - Detail lengkap sebuah title
     */
    public function show($tconst)
    {
        Log::info("=== DETAIL PAGE REQUEST ===");
        Log::info("Tconst: {$tconst}");
        Log::info("URL: " . request()->fullUrl());
        
        try {
            // VALIDASI: Pastikan tconst tidak kosong
            if (empty($tconst) || strlen($tconst) < 3) {
                Log::warning("Invalid tconst: {$tconst}");
                return $this->showError("Format ID tidak valid. Contoh: tt1234567", 404);
            }
            
            Log::info("Step 1: Fetching basic title info...");
            
            // 1. Get BASIC title info saja dulu
            $title = DB::table('dim_title')
                ->where('tconst', $tconst)
                ->select(
                    'tconst',
                    'primaryTitle',
                    'originalTitle',
                    'titleType',
                    'startYear',
                    'endYear',
                    'runtimeMinutes',
                    'isAdult'
                )
                ->first();
            
            if (!$title) {
                Log::warning("Title not found in database: {$tconst}");
                return $this->showError("Title dengan ID '{$tconst}' tidak ditemukan di database.", 404);
            }
            
            Log::info("Title found: " . ($title->primaryTitle ?? 'No title'));
            
            // 2. Get rating (optional)
            $rating = null;
            try {
                $rating = DB::table('fact_title_rating')
                    ->where('tconst', $tconst)
                    ->first();
                Log::info("Rating " . ($rating ? "found" : "not found"));
            } catch (\Exception $e) {
                Log::warning("Rating query failed: " . $e->getMessage());
            }
            
            // 3. Get cast (optional)
            $groupedCast = collect([]);
            try {
                $cast = DB::table('bridge_title_principal as btp')
                    ->leftJoin('dim_person as dp', 'btp.nconst', '=', 'dp.nconst')
                    ->leftJoin('dim_principal_category as dpc', 'btp.category_id', '=', 'dpc.category_id')
                    ->where('btp.tconst', $tconst)
                    ->select(
                        DB::raw('COALESCE(dp.primaryName, \'Unknown\') as PersonName'),
                        DB::raw('COALESCE(dpc.category_name, \'Unknown\') as Category'),
                        'btp.job',
                        'btp.characters'
                    )
                    ->orderBy('dpc.category_name')
                    ->get()
                    ->toArray();
                
                $groupedCast = collect($cast)->groupBy('Category');
                Log::info("Cast found: " . count($cast) . " people");
            } catch (\Exception $e) {
                Log::warning("Cast query failed: " . $e->getMessage());
            }
            
            // 4. Get genres (optional)
            $genres = [];
            try {
                $genres = DB::table('bridge_title_genre as btg')
                    ->join('dim_genre as dg', 'btg.genre_id', '=', 'dg.genre_id')
                    ->where('btg.tconst', $tconst)
                    ->select('dg.genre_name')
                    ->orderBy('dg.genre_name')
                    ->get()
                    ->toArray();
                Log::info("Genres found: " . count($genres));
            } catch (\Exception $e) {
                Log::warning("Genres query failed: " . $e->getMessage());
            }
            
            Log::info("=== DETAIL PAGE DATA READY ===");
            
            // Return SIMPLE view dulu untuk testing
            if (request()->has('simple') || !view()->exists('titles.show')) {
                return $this->showSimpleView($title, $rating, $groupedCast, $genres, $tconst);
            }
            
            return view('titles.show', compact('title', 'rating', 'groupedCast', 'genres'));
            
        } catch (\Exception $e) {
            Log::error("Detail page FATAL error: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            
            return $this->showError("Terjadi kesalahan sistem: " . $e->getMessage(), 500);
        }
    }
    
    /**
     * Helper untuk menampilkan view sederhana
     */
    private function showSimpleView($title, $rating, $groupedCast, $genres, $tconst)
    {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <title>' . ($title->primaryTitle ?? 'Details') . '</title>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial; padding: 20px; }
                .container { max-width: 800px; margin: 0 auto; }
                .success { color: green; }
                table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                th, td { padding: 8px; border: 1px solid #ddd; }
                th { background: #f2f2f2; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🎬 ' . ($title->primaryTitle ?? 'Movie Details') . '</h1>
                <p class="success">✓ Page loaded successfully</p>
                
                <h2>📋 Basic Info</h2>
                <table>
                    <tr><th>Field</th><th>Value</th></tr>
                    <tr><td>ID</td><td>' . $tconst . '</td></tr>
                    <tr><td>Title</td><td>' . ($title->primaryTitle ?? 'N/A') . '</td></tr>
                    <tr><td>Original Title</td><td>' . ($title->originalTitle ?? 'N/A') . '</td></tr>
                    <tr><td>Type</td><td>' . ($title->titleType ?? 'N/A') . '</td></tr>
                    <tr><td>Year</td><td>' . ($title->startYear ?? 'N/A') . '</td></tr>
                    <tr><td>Runtime</td><td>' . ($title->runtimeMinutes ? $title->runtimeMinutes . ' min' : 'N/A') . '</td></tr>
                </table>';
        
        if ($rating) {
            $html .= '<h2>⭐ Rating</h2>
                <table>
                    <tr><th>Average</th><th>Votes</th></tr>
                    <tr><td>' . ($rating->averageRating ?? 'N/A') . '/10</td>
                        <td>' . ($rating->numVotes ? number_format($rating->numVotes) : 'N/A') . '</td></tr>
                </table>';
        }
        
        if ($genres && count($genres) > 0) {
            $html .= '<h2>🏷️ Genres</h2><p>';
            foreach ($genres as $genre) {
                $html .= '<span style="background:#007bff;color:white;padding:5px 10px;margin:2px;border-radius:5px;display:inline-block;">' 
                        . ($genre->genre_name ?? '') . '</span> ';
            }
            $html .= '</p>';
        }
        
        $html .= '<h2>🔗 Links</h2>
                <p>
                    <a href="/">🏠 Home</a> | 
                    <a href="/search">🔍 Search</a> | 
                    <a href="/search?q=' . urlencode($title->primaryTitle ?? '') . '">Find Similar</a>
                </p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Helper untuk menampilkan error
     */
    private function showError($message, $statusCode = 500)
    {
        $tconst = request()->segment(2) ?? 'unknown';
        
        if (view()->exists('errors.custom')) {
            return response()->view('errors.custom', [
                'error' => $message,
                'tconst' => $tconst
            ], $statusCode);
        }
        
        return response("
            <h1>{$statusCode} Error</h1>
            <p><strong>{$message}</strong></p>
            <p>Title ID: {$tconst}</p>
            <a href='/'>Home</a> | <a href='/search'>Search</a>
        ", $statusCode);
    }

    /**
     * BY GENRE - Filter title berdasarkan genre
     */
    public function byGenre($genre)
    {
        try {
            Log::info("Loading titles by genre: {$genre}");
            
            $titles = [];
            
            try {
                // Panggil stored procedure sp_Title_GetByGenre
                $titles = DB::select('EXEC sp_Title_GetByGenre ?', [$genre]);
            } catch (\Exception $e) {
                Log::warning("Genre SP failed: " . $e->getMessage());
                // Fallback query
                $titles = DB::select('
                    SELECT 
                        dt.tconst,
                        dt.primaryTitle,
                        dg.genre_name
                    FROM bridge_title_genre btg
                    JOIN dim_title dt ON btg.tconst = dt.tconst
                    JOIN dim_genre dg ON btg.genre_id = dg.genre_id
                    WHERE dg.genre_name = ?
                ', [$genre]);
            }
            
            // Ambil daftar genre untuk dropdown
            $allGenres = DB::table('dim_genre')
                ->orderBy('genre_name')
                ->pluck('genre_name')
                ->toArray();
            
            return view('titles.by-genre', compact('titles', 'genre', 'allGenres'));
            
        } catch (\Exception $e) {
            Log::error("Genre page error: " . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal memuat data genre: ' . $e->getMessage()]);
        }
    }
}
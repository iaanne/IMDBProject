<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Wajib tambah ini

class HomeController extends Controller
{
    public function home()
    {
        // Waktu simpan cache dalam detik (3600 detik = 1 jam)
        $seconds = 3600;

        // 1. Featured
        $featuredMovie = Cache::remember('home_featured', $seconds, function () {
            $data = DB::select('EXEC sp_GetFeaturedMovie');
            return $data[0] ?? null;
        });

        // 2. Top 10
        $topMovies = Cache::remember('home_top_10', $seconds, function () {
            return DB::select('EXEC sp_PopularMovies @top = 10');
        });

        // 3. Rekomendasi
        $recommended = Cache::remember('home_recommended', $seconds, function () {
            return DB::select('EXEC sp_GetRecommended @top = 12');
        });

        // 4. Seasonal Content
        $seasonalContent = Cache::remember('home_seasonal', $seconds, function () {
            return DB::select('EXEC sp_GetSeasonalContent @top = 12');
        });

        // 5. Genre-based Content
        $actionMovies = Cache::remember('genre_action', $seconds, function () {
            return DB::select("EXEC sp_GetMixByGenre @GenreName = 'Action'");
        });

        $ComedyMovies = Cache::remember('genre_comedy', $seconds, function () {
            return DB::select("EXEC sp_GetMixByGenre @GenreName = 'Comedy'");
        });

        $familyMovies = Cache::remember('genre_family', $seconds, function () {
            return DB::select("EXEC sp_GetMixByGenre @GenreName = 'Family'");
        });

        $animationMovies = Cache::remember('genre_animation', $seconds, function () {
            return DB::select("EXEC sp_GetMixByGenre @GenreName = 'Animation'");
        });

        $romanceMovies = Cache::remember('genre_romance', $seconds, function () {
            return DB::select("EXEC sp_GetMixByGenre @GenreName = 'Romance'");
        });

        $kidsMovies = Cache::remember('genre_kids', $seconds, function () {
            return DB::select("EXEC sp_GetMixByGenre @GenreName = 'Family'");
        });

        return view('home', compact(
            'featuredMovie', 'topMovies', 'recommended', 'seasonalContent', 
            'actionMovies', 'ComedyMovies', 'familyMovies', 
            'animationMovies', 'kidsMovies', 'romanceMovies'
        ));
    }
}
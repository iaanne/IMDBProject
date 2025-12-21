<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
   public function home()
    {
        // 1. Featured
        $featuredMovieData = DB::select('EXEC sp_GetFeaturedMovie');
        $featuredMovie = $featuredMovieData[0] ?? null;

        // 2. Top 10
        $topMovies = DB::select('EXEC sp_PopularMovies @top = 10');

        // 3. Rekomendasi
        $recommended = DB::select('EXEC sp_GetRecommended @top = 12');

        // 4. === (BARU) SEASONAL CONTENT ===
        $seasonalContent = DB::select('EXEC sp_GetSeasonalContent @top = 12');

        // Pastikan tulisan 'Action', 'Romance' ini ada di kolom genre_name tabel dim_genre kamu
        $actionMovies    = DB::select("EXEC sp_GetMixByGenre @GenreName = 'Action'");
        $ComedyMovies    = DB::select("EXEC sp_GetMixByGenre @GenreName = 'Comedy'");
        $familyMovies    = DB::select("EXEC sp_GetMixByGenre @GenreName = 'Family'");
        $animationMovies = DB::select("EXEC sp_GetMixByGenre @GenreName = 'Animation'");
        $romanceMovies   = DB::select("EXEC sp_GetMixByGenre @GenreName = 'Romance'");

        
        // Khusus Kids, kita pinjam genre Family lagi tapi diacak ulang
        $kidsMovies      = DB::select("EXEC sp_GetMixByGenre @GenreName = 'Family'"); 


        return view('home', compact('featuredMovie', 'topMovies', 'recommended', 'seasonalContent', 'actionMovies',
            'ComedyMovies',
            'familyMovies',
            'animationMovies',
            'kidsMovies', 
            'romanceMovies',));
    }
}

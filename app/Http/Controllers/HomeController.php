<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        // 1. Film untuk Hero Preview (hanya satu)
        $featuredMovieData = DB::select('EXEC sp_GetFeaturedMovie');
        $featuredMovie = $featuredMovieData[0] ?? null; // Ambil baris pertama atau null

        // 2. Film Terpopuler (Top 10)
        $topMovies = DB::select('EXEC sp_PopularMovies @top = 10');

        // 3. Film Rekomendasi
        $recommendedMovies = DB::select('EXEC sp_GetRecommendedMovies @top = 12');

        return view('home', compact('featuredMovie', 'topMovies', 'recommendedMovies'));
    }
}

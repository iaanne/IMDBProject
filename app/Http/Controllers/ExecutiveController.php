<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class ExecutiveController extends Controller
{
    public function dashboard()
    {
        // Bungkus semua DB::select dengan collect()
        
        $topMovies = collect(DB::select("EXEC sp_Exec_TopRatedMovies"));

        $topTVShows = collect(DB::select("EXEC sp_Exec_TopTVShows"));

        $genrePopularity = collect(DB::select("EXEC sp_Exec_GenrePopularity"));

        $actorProductivity = collect(DB::select("EXEC sp_Exec_ActorProductivity"));

        $ratingTrend = collect(DB::select("EXEC sp_Exec_RatingTrendPerYear"));

        return view('executive.dashboard', compact(
            'topMovies', 
            'topTVShows', 
            'genrePopularity', 
            'actorProductivity', 
            'ratingTrend'
        ));
    }
}
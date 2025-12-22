<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Cache;

class ExecutiveController extends Controller
{

public function dashboard()
{
    // Simpan hasil query dalam cache selama 60 menit
    $data = Cache::remember('executive_dashboard_data', 3600, function () {
        return [
            'topMovies'         => collect(DB::select("EXEC sp_Exec_TopRatedMovies")),
            'topTVShows'        => collect(DB::select("EXEC sp_Exec_TopTVShows")),
            'genrePopularity'   => collect(DB::select("EXEC sp_Exec_GenrePopularity")),
            'actorProductivity' => collect(DB::select("EXEC sp_Exec_ActorProductivity")),
            'ratingTrend'       => collect(DB::select("EXEC sp_Exec_RatingTrendPerYear")),
        ];
    });

    return view('executive.dashboard', $data);
}
}
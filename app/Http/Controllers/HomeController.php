<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
{
    $results = DB::connection('sqlsrv')
        ->select('select top 10 * from vw_TopRatedTitles');
    $popular = DB::select("EXEC sp_PopularMovies");
    $seasonal = DB::select("EXEC sp_MoviesByYear ?", [2024]);
    
    return view('home', [
        'top10' => $results,
        'popular' => $popular,
        'seasonal' => $seasonal
    ]);
}
}

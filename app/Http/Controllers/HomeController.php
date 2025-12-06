<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
{
    $results = DB::connection('sqlsrv')
        ->select('select top 5 * from vw_TopRatedTitles');
    
    return view('home', [
        'top10' => $results
    ]);
}
}

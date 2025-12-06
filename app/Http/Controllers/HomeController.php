<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        $top10 = DB::select("EXEC dbo.sp_Analytics_TopRatedTitles @top = 10");

        return view('home', [
            'top10' => $top10
        ]);
    }
}

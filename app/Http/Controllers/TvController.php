<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Tambahkan ini

class TvController extends Controller
{
    public function index()
{
    // Ambil top TV shows (pakai SP yang ada)
    $topShows = DB::select('EXEC sp_GetPopularTvShows @limit = 8');

    // Ambil networks (pakai SP yang ada)
    $networks = DB::select('EXEC sp_GetAllTvNetworks');

    return view('tv.index', [
        'topShows' => $topShows,
        'networks' => $networks
    ]);
}

}
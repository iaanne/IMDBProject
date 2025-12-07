<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Tambahkan ini

class TvController extends Controller
{
    public function index()
    {
        // 1. Ambil 8 serial TV teratas berdasarkan rating
        $topShows = DB::select('EXEC sp_GetTopRatedShows @top = 8');

        // 2. Ambil semua jaringan TV
        $networks = DB::select('EXEC sp_GetAllNetworks');

        // 3. Kirim data ke view
        return view('tv.index', [
            'topShows' => $topShows,
            'networks' => $networks
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Tambahkan ini

class FilmsController extends Controller
{
    public function index()
    {
        // 1. Ambil 8 film teratas berdasarkan rating
        $topFilms = DB::select('EXEC sp_GetTopRatedFilms @top = 8');

        // 2. Ambil semua genre
        $genres = DB::select('EXEC sp_GetAllGenres');

        // 3. Kirim data ke view
        return view('films.index', [
            'topFilms' => $topFilms,
            'genres' => $genres
        ]);
    }
}
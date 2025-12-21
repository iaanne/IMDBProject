<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Wajib ada agar Cache bisa jalan

class GenreController extends Controller
{
    public function index()
{
    // Langsung ambil data dari DB tanpa cache
    $genres = DB::select("SELECT DISTINCT genre_name FROM dim_genre ORDER BY genre_name ASC");
    
    return view('genres.index', compact('genres'));
}

public function show($name)
{
    // Langsung ambil data dari DB tanpa cache
    $titles = DB::select("EXEC sp_GetTitlesByGenre @genre_name = ?", [$name]);

    return view('genres.show', compact('titles', 'name'));
}
}
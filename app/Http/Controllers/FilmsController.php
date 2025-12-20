<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class FilmsController extends Controller
{
    public function index()
    {
        // Panggil SP untuk film teratas
        $topFilms = DB::select('EXEC sp_GetPopularFilms @limit = 12');

        $genres   = DB::select('EXEC sp_Exec_GenrePopularity');

        return view('films.index', compact('topFilms', 'genres'));
    }
}
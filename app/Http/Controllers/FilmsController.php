<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class FilmsController extends Controller
{
    public function index()
    {
        // Panggil SP untuk film teratas
        $topFilms = DB::select('EXEC sp_Exec_TopRatedMovies @top = 12');
        return view('films.index', compact('topFilms'));
    }
}
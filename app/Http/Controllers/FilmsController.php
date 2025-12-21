<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class FilmsController extends Controller
{
    public function index(Request $request)
{
    // 1. Film Terpopuler (Top 10) - Ini SP PopularMovies harusnya sudah di-set 'movie' di awal banget, jadi aman.
    $topFilms = DB::select('EXEC sp_GetPopularFilms');

    // 2. Top 10 Tahun Ini (PAKAI PARAMETER BARU)
    $currentYearFilms = DB::select('EXEC sp_GetSeasonalFilms @top = 10');

    // 3. Film Berdasarkan Genre (PAKAI PARAMETER BARU)
    // Kita kunci @OnlyMovies = 1 biar serial TV gak masuk sini
    $actionFilms    = DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Action', @Top = 6, @OnlyMovies = 1");
    $comedyFilms    = DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Comedy', @Top = 6, @OnlyMovies = 1");
    $familyFilms    = DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Family', @Top = 6, @OnlyMovies = 1");
    $animationFilms = DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Animation', @Top = 6, @OnlyMovies = 1");
    $romanceFilms   = DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Romance', @Top = 6, @OnlyMovies = 1");
    $kidsFilms      = DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Family', @Top = 6, @OnlyMovies = 1");

    $genres = DB::select('SELECT DISTINCT genre_name FROM dim_genre');

    // Ambil film dengan jumlah penonton (votes) terbanyak
    $mostWatchedFilms = DB::select('EXEC sp_GetMostWatchedFilms @limit = 10');

    return view('films.index', compact(
        'topFilms', 'currentYearFilms', 'mostWatchedFilms', 'actionFilms', 'comedyFilms', 
        'familyFilms', 'animationFilms', 'romanceFilms', 'kidsFilms', 'genres'
    ));
}
}
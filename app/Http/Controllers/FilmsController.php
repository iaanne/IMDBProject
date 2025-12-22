<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
class FilmsController extends Controller
{
    public function index(Request $request)
{
    // Simpan semua data film ke dalam cache selama 1 hari (86400 detik)
    $data = Cache::remember('films_index_data', 86400, function () {
        return [
            'topFilms'         => DB::select('EXEC sp_GetPopularFilms'),
            'currentYearFilms' => DB::select('EXEC sp_GetSeasonalFilms @top = 10'),
            'actionFilms'      => DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Action', @Top = 6, @OnlyMovies = 1"),
            'comedyFilms'      => DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Comedy', @Top = 6, @OnlyMovies = 1"),
            'familyFilms'      => DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Family', @Top = 6, @OnlyMovies = 1"),
            'animationFilms'   => DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Animation', @Top = 6, @OnlyMovies = 1"),
            'romanceFilms'     => DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Romance', @Top = 6, @OnlyMovies = 1"),
            'kidsFilms'        => DB::select("EXEC sp_GetMoviesByGenre @GenreName = 'Family', @Top = 6, @OnlyMovies = 1"),
            'genres'           => DB::select('SELECT DISTINCT genre_name FROM dim_genre'),
            'mostWatchedFilms' => DB::select('EXEC sp_GetMostWatchedFilms @limit = 10'),
        ];
    });

    return view('films.index', $data);
}
}
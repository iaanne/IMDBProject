<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TitleController extends Controller
{
    // ===========================
    //       SEARCH
    // ===========================
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        if (!$keyword) {
            return view('titles.search', [
                'results' => [],
                'keyword' => ''
            ]);
        }


        try {
            // Kirim keyword apa adanya, biar Stored Procedure yang menanganinya
            $results = DB::select(
                "EXEC sp_SearchTitle @keyword = :keyword",
                ['keyword' => $keyword]
            );
        } catch (\Exception $e) {
            return view('titles.search', [
                'results' => [],
                'keyword' => $keyword,
                'error' => $e->getMessage()
            ]);
        }

        return view('titles.search', [
            'results' => $results,
            'keyword' => $keyword
        ]);
    }



    // ===========================
    //       DETAIL PAGE
    // ===========================
   public function show($tconst)
{
    // =====================
    // DETAIL
    // =====================
    $detail = DB::select('EXEC sp_GetMovieDetail ?', [$tconst]);
    if (!$detail || count($detail) === 0) {
        abort(404, 'Title not found');
    }
    $detail = $detail[0];

    // =====================
    // RATING
    // =====================
    $rating = DB::select('EXEC sp_GetMovieRating ?', [$tconst]);
    $rating = $rating[0] ?? null;

    // =====================
    // CAST + CREW
    // =====================
    $principals = DB::select('EXEC sp_GetMovieCast ?', [$tconst]);

    $cast = array_filter(
        $principals,
        fn ($p) => in_array($p->Category, ['actor', 'actress'])
    );

    $crew = array_filter(
        $principals,
        fn ($p) => !in_array($p->Category, ['actor', 'actress'])
    );

    // =====================
    // GENRE (SP BARU)
    // =====================
    $genres = DB::select('EXEC sp_GetMovieGenres ?', [$tconst]);

    return view('titles.show', compact(
        'detail',
        'rating',
        'cast',
        'crew',
        'genres'
    ));
}

}

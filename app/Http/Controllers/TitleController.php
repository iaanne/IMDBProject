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
        // Panggil SP dengan named parameter
        $results = DB::select(
            "EXEC sp_Title_Search @keyword = :keyword",
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
        // DETAIL
        $detail = DB::select('EXEC sp_Title_GetDetail ?', [$tconst]);
        if (!$detail || count($detail) == 0) {
            return abort(404, "Title not found");
        }
        $detail = $detail[0];

        // RATING
        $rating = DB::select('EXEC sp_Title_GetRating ?', [$tconst]);
        $rating = $rating[0] ?? null;

        // CAST
        $cast = DB::select('EXEC sp_Title_GetCast ?', [$tconst]);

        // GENRE — karena SP ini TIDAK ADA di file SQL kamu
        // Maka diganti query native
        $genres = DB::select('EXEC sp_Title_GetDetailGenre ?', [$tconst]);

        return view('titles.search', [
    'detail' => $detail,
    'rating' => $rating,
    'genres' => $genres,
    'cast' => $cast,
    'results' => [],
    'keyword' => null
]);

    }
}

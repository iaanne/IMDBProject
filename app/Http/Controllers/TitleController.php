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
        // Detail
        $title = DB::select("EXEC sp_Title_GetDetail ?", [$tconst]);
        $title = $title[0] ?? null;

        if (!$title) {
            abort(404, "Title not found.");
        }

        // Rating
        $rating = DB::select("EXEC sp_Title_GetRating ?", [$tconst]);
        $rating = $rating[0] ?? null;

        // Cast
        $cast = DB::select("EXEC sp_Title_GetCast ?", [$tconst]);

        // GENRE — karena SP ini TIDAK ADA di file SQL kamu
        // Maka diganti query native
        $genres = DB::select("
            SELECT dg.genre_name
            FROM bridge_title_genre btg
            JOIN dim_genre dg ON btg.genre_id = dg.genre_id
            WHERE btg.tconst = ?
        ", [$tconst]);

        return view('titles.show', [
            'title' => $title,
            'rating' => $rating,
            'cast' => $cast,
            'genres' => $genres
        ]);
    }
}

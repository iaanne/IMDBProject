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

        // Panggil SP
        $results = DB::select('EXEC sp_Title_Search @keyword = ?', [$keyword]);

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
        // 1. Detail judul
        $title = DB::select('EXEC sp_Title_GetDetail @tconst = ?', [$tconst]);
        $title = $title[0] ?? null;

        if (!$title) {
            abort(404, "Title not found.");
        }

        // 2. Rating
        $rating = DB::select('EXEC sp_Title_GetRating @tconst = ?', [$tconst]);
        $rating = $rating[0] ?? null;

        // 3. Cast (aktor, sutradara, crew)
        $cast = DB::select('EXEC sp_Title_GetCast @tconst = ?', [$tconst]);

        // 4. Genre
        $genres = DB::select('EXEC sp_Title_GetDetailGenre @tconst = ?', [$tconst]);


        return view('titles.show', [
            'title' => $title,
            'rating' => $rating,
            'cast' => $cast,
            'genres' => $genres
        ]);
    }
}

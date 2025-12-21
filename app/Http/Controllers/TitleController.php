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
    // 1. AMBIL DETAIL
    $detail = DB::select("EXEC sp_GetMovieDetail :tconst", ['tconst' => $tconst]);
    
    if (!$detail || count($detail) === 0) {
        abort(404, 'Title not found');
    }
    $detail = $detail[0];

    // 2. AMBIL RATING
    $rating = DB::select("EXEC sp_GetMovieRating :tconst", ['tconst' => $tconst]);
    $rating = $rating[0] ?? null;

    // 3. AMBIL CAST & CREW
    $principals = DB::select("EXEC sp_GetMovieCast :tconst", ['tconst' => $tconst]);

    $cast = array_filter(
        $principals,
        fn ($p) => in_array(strtolower($p->Category), ['actor', 'actress', 'self'])
    );

    $crew = array_filter(
        $principals,
        fn ($p) => !in_array(strtolower($p->Category), ['actor', 'actress', 'self'])
    );

    // 4. AMBIL GENRES (Ini yang tadi error karena belum sempat diambil)
    $genres = DB::select("EXEC sp_GetMovieGenres :tconst", ['tconst' => $tconst]);

    // 5. CEK WATCHLIST
    $isInWatchlist = false;
    if (auth()->check()) {
        $isInWatchlist = \App\Models\Watchlist::where('user_id', auth()->id())
                        ->where('tconst', $tconst)
                        ->exists();
    }

    // 6. KIRIM SEMUA KE VIEW (Hanya ada SATU return di paling bawah)
    return view('titles.show', compact(
        'detail',
        'rating',
        'cast',
        'crew',
        'genres',
        'isInWatchlist'
    ));
}}

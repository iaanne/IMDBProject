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
            // --- TAMBAHAN & PERUBAHAN DIMULAI DI SINI ---

            // 1. Bersihkan keyword dari spasi berlebih di awal/akhir
            $cleanKeyword = trim($keyword);

            // 2. Pecah keyword menjadi array kata-kata
            $words = explode(' ', $cleanKeyword);

            // 3. Filter agar tidak ada kata kosong (jika ada spasi ganda)
            $words = array_filter($words);

            // 4. Jika ada kata-kata, format untuk query CONTAINS
            if (count($words) > 0) {
                // Bungkus setiap kata dengan tanda kutip
                $quotedWords = array_map(function ($word) {
                    return '"' . $word . '"';
                }, $words);

                // Gabungkan dengan ' OR ' untuk mencari judul yang mengandung SALAH SATU kata tersebut
                $formattedKeyword = implode(' OR ', $quotedWords);
            } else {
                // Jika keyword kosong setelah dibersihkan, set ke string kosong
                $formattedKeyword = '';
            }

            // --- PERUBAHAN SELESAI ---

            // Panggil SP dengan keyword yang sudah diformat
            $results = DB::select(
                "EXEC sp_SearchTitle @keyword = :keyword",
                ['keyword' => $formattedKeyword] // Gunakan $formattedKeyword
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

        $crew = DB::select('EXEC sp_Title_GetCrew ?', [$tconst]);

        return view('titles.show', [
            'detail' => $detail,
            'rating' => $rating,
            'genres' => $genres,
            'cast' => $cast,
            'crew' => $crew,
            'results' => [],
            'keyword' => null
        ]);
    }
}

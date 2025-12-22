<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // 1. Samakan input keyword (gunakan 'q' atau 'keyword' secara konsisten)
        $query = $request->input('q') ?? $request->input('keyword'); 

        if (!$query) {
            return view('search.index', ['results' => [], 'query' => '']);
        }

        // 2. Gunakan Stored Procedure (SP) yang sudah kita buat tadi
        // SP ini sudah menghandle pencarian cerdas dan urutan (Ranking)
        $results = DB::select("EXEC sp_SearchTitle @keyword = ?", [$query]);

        // 3. Kembalikan ke view dengan format yang benar
        return view('search.index', [
            'results' => $results,
            'keyword' => $query,
            'query'   => $query
        ]);
    }
}
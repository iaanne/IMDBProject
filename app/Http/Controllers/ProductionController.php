<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function dashboard()
    {
        // Statistik untuk production
        $totalMovies = DB::select("SELECT COUNT(*) as total FROM dim_title WHERE titleType = 'movie'")[0]->total;
        $totalShows = DB::select("SELECT COUNT(*) as total FROM dim_show")[0]->total;
        
        return view('production.dashboard', compact('totalMovies', 'totalShows'));
    }

    public function indexMovies()
    {
        $movies = DB::select("SELECT TOP 50 * FROM dim_title WHERE titleType = 'movie' ORDER BY startYear DESC");
        return view('production.movies.index', compact('movies'));
    }

    public function createMovie()
    {
        return view('production.movies.create');
    }

    public function storeMovie(Request $request)
    {
        $request->validate([
            'tconst' => 'required|unique:dim_title,tconst',
            'primaryTitle' => 'required',
            'startYear' => 'required|integer',
        ]);

        DB::statement('EXEC sp_InsertTitle @tconst = ?, @primaryTitle = ?, @titleType = ?, @startYear = ?', [
            $request->tconst,
            $request->primaryTitle,
            'movie',
            $request->startYear
        ]);

        return redirect()->route('production.movies.index')
            ->with('success', 'Film berhasil ditambahkan!');
    }

    public function destroyMovie($tconst)
    {
        DB::statement('EXEC sp_DeleteTitle @tconst = ?', [$tconst]);

        return redirect()->route('production.movies.index')
            ->with('success', 'Film berhasil dihapus!');
    }
}
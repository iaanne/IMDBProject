<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExecutiveController extends Controller
{

    public function dashboard()
    {
        try {
            // Ambil semua data dari Stored Procedures
            $topMovies = collect(DB::select('EXEC sp_Exec_TopRatedMovies @top = ?', [10]));
            $genrePopularity = collect(DB::select('EXEC sp_Exec_GenrePopularity'));
            $actorProductivity = collect(DB::select('EXEC sp_Exec_ActorProductivity'));
            $ratingTrend = collect(DB::select('EXEC sp_Exec_RatingTrendPerYear'));
            $topTVShows = collect(DB::select('EXEC sp_Exec_TopTVShows @top = ?', [10]));
            
            return view('executive.dashboard', compact(
                'topMovies', 
                'genrePopularity', 
                'actorProductivity', 
                'ratingTrend', 
                'topTVShows'
            ));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }

    public function topMovies()
    {
        try {
            $topMovies = collect(DB::select('EXEC sp_Exec_TopRatedMovies @top = ?', [50]));
            return view('executive.top-movies', compact('topMovies'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function genrePopularity()
    {
        try {
            $genres = collect(DB::select('EXEC sp_Exec_GenrePopularity'));
            return view('executive.genre-popularity', compact('genres'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function ratingTrends()
    {
        try {
            $trends = collect(DB::select('EXEC sp_Exec_RatingTrendPerYear'));
            return view('executive.rating-trends', compact('trends'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }
}
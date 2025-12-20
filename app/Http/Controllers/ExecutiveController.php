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
            // NAIKIN TIMEOUT khusus untuk dashboard ini
            config(['database.connections.sqlsrv.timeout' => 120]); // 2 menit
            
            // 1. Top Movies
            $topMovies = collect(DB::select('EXEC sp_Exec_TopRatedMovies @top = ?', [10]));
            
            // 2. Genre Popularity
            $genrePopularity = collect(DB::select('EXEC sp_Exec_GenrePopularity'));
            
            // 3. Actor Productivity (LAMBAT - tapi dibiarkan jalan)
            try {
                set_time_limit(180); // PHP timeout 3 menit
                $actorProductivity = collect(DB::select('EXEC sp_Exec_ActorProductivity @top = ?', [15]));
            } catch (\Exception $e) {
                // Kalau timeout, log error dan pakai array kosong
                \Log::error('Actor Productivity Error: ' . $e->getMessage());
                $actorProductivity = collect([]);
            }
            
            // 4. Rating Trend Per Year
            $ratingTrend = collect(DB::select('EXEC sp_Exec_RatingTrendPerYear'));
            
            // 5. Top TV Shows
            $topTVShows = collect(DB::select('EXEC sp_Exec_TopTVShows @top = ?', [10]));
            
            // Debug - Uncomment untuk cek data
            // dd(compact('topMovies', 'genrePopularity', 'actorProductivity', 'ratingTrend', 'topTVShows'));
            

            return view('executive.dashboard', compact(
                'topMovies', 
                'genrePopularity', 
                'actorProductivity', 
                'ratingTrend', 
                'topTVShows'
            ));
            
            // Simpan hasil query dalam cache selama 24 jam (86400 detik)
    $actors = Cache::remember('top_productive_actors', 86400, function () {
        return DB::select('EXEC sp_Exec_ActorProductivity @top = ?', [15]);
    });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }
}
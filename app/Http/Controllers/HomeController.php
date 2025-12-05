<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller

{
    public function index()
    {

        // $movies = Movie::limit(5)->get();

        // dd(DB::connection());

        // $movie = Movie::where('primaryTitle', 'La La Land')->first();

        // $movie = Movie::first();

        // dd($movies);

        $featuredMovie = Movie::where('runtimeMinutes', '>', 80)
            ->whereNotNull('startYear')
            ->inRandomOrder()
            ->first();

        $trendingMovies = Movie::orderBy('startYear', 'desc')
            ->whereNotNull('primaryTitle')
            ->limit(15)
            ->get();

        $newReleases = Movie::orderBy('startYear', 'desc')
            ->whereNotNull('primaryTitle')
            ->limit(15)
            ->offset(15)
            ->get();

        $topRomanceMovies = Movie::where('genre_name', 'like', '%Comedy%')
            ->orderBy('startYear', 'desc')
            ->limit(10)
            ->get();

        $comedyMovies = Movie::where('genres', 'like', '%Comedy%')
            ->orderBy('startYear', 'desc')
            ->limit(15)
            ->get();


        return view('home', compact(
            'featuredMovie',
            'trendingMovies',
            'newReleases',
            'topRomanceMovies',
            'comedyMoviees'
        ));
    }
}

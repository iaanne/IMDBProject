<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TVShowController;


Route::get('/', [HomeController::class, 'index']);


// Director list page
Route::get('/director', [DirectorController::class, 'index'])->name('director.index');

// Artist list page
Route::get('/artist', [ArtistController::class, 'index'])->name('artist.index');

// Films
Route::get('/films', [MovieController::class, 'index'])->name('films.index');

// TV Show
Route::get('/tv-shows', [TVShowController::class, 'index'])->name('tv.index');

Route::get('/test-db', function () {
    try {
        return DB::select('SELECT TOP 5 * FROM title_basics');
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

// Movies
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');


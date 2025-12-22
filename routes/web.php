<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController, TitleController, FilmsController, 
    TvController, AuthController, ExecutiveController, 
    ProductionController, WatchlistController, RegisterController, GenreController
};

// =========================================================
// 1. PUBLIC ROUTES (Bisa diakses siapa saja)
// =========================================================
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/search', [TitleController::class, 'search'])->name('titles.search');
Route::get('/title/{tconst}', [TitleController::class, 'show'])->name('titles.show');
Route::get('/films', [FilmsController::class, 'index'])->name('films.index');
Route::get('/tv-shows', [TvController::class, 'index'])->name('tv.index');

// Genre Routes
Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::get('/genres/{name}', [GenreController::class, 'show'])->name('genres.show');
Route::get('/genre/{genre}', [TitleController::class, 'byGenre'])->name('titles.byGenre');

// Authentication & Registration
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Testing Route
Route::get('/cek-file', function () {
    return view('production.movies.index');
});

// =========================================================
// 2. PROTECTED ROUTES (Harus Login)
// =========================================================
Route::middleware(['auth'])->group(function () {
    
    // Watchlist
    Route::post('/watchlist/toggle', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');
    Route::get('/my-watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');

    // API Helpers
    Route::get('/api/search-series', [ProductionController::class, 'searchSeries'])->name('api.searchSeries');

    // -----------------------------------------------------
    // EXECUTIVE DASHBOARD (Role: Executive)
    // -----------------------------------------------------
    Route::middleware(['role:executive'])->prefix('executive')->name('executive.')->group(function () {
        Route::get('/dashboard', [ExecutiveController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics/top-movies', [ExecutiveController::class, 'topMovies'])->name('topMovies');
        Route::get('/analytics/genre-popularity', [ExecutiveController::class, 'genrePopularity'])->name('genrePopularity');
        Route::get('/analytics/rating-trends', [ExecutiveController::class, 'ratingTrends'])->name('ratingTrends');
        
        // Alias tambahan jika dibutuhkan oleh view lama
        Route::get('/top-movies', [ExecutiveController::class, 'topMovies'])->name('top-movies');
        Route::get('/genre-popularity', [ExecutiveController::class, 'genrePopularity'])->name('genre-popularity');
        Route::get('/rating-trends', [ExecutiveController::class, 'ratingTrends'])->name('rating-trends');
    });

    // -----------------------------------------------------
    // PRODUCTION DASHBOARD (Role: Production)
    // -----------------------------------------------------
    Route::middleware(['role:production'])->prefix('production')->name('production.')->group(function () {
        Route::get('/dashboard', [ProductionController::class, 'dashboard'])->name('dashboard');
        
        // Movies Management
        Route::get('/movies', [ProductionController::class, 'indexMovies'])->name('movies.index');
        Route::get('/movies/create', [ProductionController::class, 'createMovie'])->name('movies.create');
        Route::post('/movies', [ProductionController::class, 'storeMovie'])->name('movies.store');
        Route::get('/movies/{tconst}/edit', [ProductionController::class, 'editMovie'])->name('movies.edit');
        Route::put('/movies/{tconst}', [ProductionController::class, 'updateMovie'])->name('movies.update');
        Route::delete('/movies/{tconst}', [ProductionController::class, 'destroyMovie'])->name('movies.destroy');

        // Shows Management
        Route::get('/shows', [ProductionController::class, 'indexShows'])->name('shows.index');
        Route::get('/shows/create', [ProductionController::class, 'createShow'])->name('shows.create');
        Route::post('/shows', [ProductionController::class, 'storeShow'])->name('shows.store');
        Route::get('/shows/{show_id}/edit', [ProductionController::class, 'editShow'])->name('shows.edit');
        Route::put('/shows/{show_id}', [ProductionController::class, 'updateShow'])->name('shows.update');

        // Episodes Management
        Route::get('/episodes', [ProductionController::class, 'indexEpisodes'])->name('episodes.index');
        Route::get('/episodes/create', [ProductionController::class, 'createEpisode'])->name('episodes.create');
        Route::post('/episodes', [ProductionController::class, 'storeEpisode'])->name('episodes.store');
        Route::get('/episodes/{tconst}/edit', [ProductionController::class, 'editEpisode'])->name('episodes.edit');
        Route::put('/episodes/{tconst}', [ProductionController::class, 'updateEpisode'])->name('episodes.update');
    });
});
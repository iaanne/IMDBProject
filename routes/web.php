<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\FilmsController;
use App\Http\Controllers\TvController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\ProductionController;

// HOME PAGE — gunakan HomeController saja!
Route::get('/', [HomeController::class, 'home'])->name('home');

// SEARCH
Route::get('/search', [TitleController::class, 'search'])->name('titles.search');

// DETAIL TITLE
Route::get('/title/{tconst}', [TitleController::class, 'show'])->name('titles.show');

// GENRE PAGE
Route::get('/genre/{genre}', [TitleController::class, 'byGenre'])->name('titles.byGenre');

// FILMS PAGE
Route::get('/films', [FilmsController::class, 'index'])->name('films.index');

// TV SHOW PAGE
Route::get('/tv-shows', [TvController::class, 'index'])->name('tv.index');

// // POPULAR MOVIES PAGE
// Route::get('/popular', [TitleController::class, 'popular'])->name('titles.popular');

// // SEASONAL MOVIES PAGE
// Route::get('/year', [TitleController::class, 'moviesByYear'])->name('titles.byYear');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (harus login dulu)
Route::middleware(['auth'])->group(function () {
    // Executive Dashboard
    Route::get('/executive/dashboard', function() {
        return view('executive.dashboard');
    })->name('executive.dashboard');
    
    // Production Dashboard
    Route::get('/production/dashboard', function() {
        return view('production.dashboard');
    })->name('production.dashboard');
});

// =================== PUBLIC ROUTES (Semua bisa akses) ===================
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/films', [FilmsController::class, 'index'])->name('films.index');
Route::get('/tv-shows', [TvController::class, 'index'])->name('tv.index');
Route::get('/search', [TitleController::class, 'search'])->name('search');
Route::get('/title/{tconst}', [TitleController::class, 'show'])->name('titles.show');
Route::get('/genre/{genre}', [TitleController::class, 'byGenre'])->name('titles.byGenre');

// =================== AUTH ROUTES ===================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =================== EXECUTIVE ROUTES (Hanya Executive) ===================
Route::middleware(['auth', 'role:executive'])->prefix('executive')->group(function () {
    Route::get('/dashboard', [ExecutiveController::class, 'dashboard'])->name('executive.dashboard');
    Route::get('/analytics/top-movies', [ExecutiveController::class, 'topMovies'])->name('executive.topMovies');
    Route::get('/analytics/genre-popularity', [ExecutiveController::class, 'genrePopularity'])->name('executive.genrePopularity');
    Route::get('/analytics/rating-trends', [ExecutiveController::class, 'ratingTrends'])->name('executive.ratingTrends');
});

// =================== PRODUCTION ROUTES (Hanya Production) ===================
Route::middleware(['auth', 'role:production'])->prefix('production')->group(function () {
    Route::get('/dashboard', [ProductionController::class, 'dashboard'])->name('production.dashboard');
    
    // CRUD Movies
    Route::get('/movies', [ProductionController::class, 'indexMovies'])->name('production.movies.index');
    Route::get('/movies/create', [ProductionController::class, 'createMovie'])->name('production.movies.create');
    Route::post('/movies', [ProductionController::class, 'storeMovie'])->name('production.movies.store');
    Route::get('/movies/{tconst}/edit', [ProductionController::class, 'editMovie'])->name('production.movies.edit');
    Route::put('/movies/{tconst}', [ProductionController::class, 'updateMovie'])->name('production.movies.update');
    Route::delete('/movies/{tconst}', [ProductionController::class, 'destroyMovie'])->name('production.movies.destroy');
});


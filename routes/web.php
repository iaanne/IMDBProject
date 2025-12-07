<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\FilmsController;
use App\Http\Controllers\TvController;

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

// POPULAR MOVIES PAGE
Route::get('/popular', [TitleController::class, 'popular'])->name('titles.popular');
// SEASONAL MOVIES PAGE
Route::get('/year', [TitleController::class, 'moviesByYear'])->name('titles.byYear');

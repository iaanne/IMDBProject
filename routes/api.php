<?php

use App\Http\Controllers\TitleController;
// use App\Http\Controllers\AnalyticsController;


Route::get('/', function () {
    return ['message' => 'API is running'];
});



// PUBLIC ROUTES - NO AUTH
Route::get('/titles/health', [TitleController::class, 'healthCheck']);
Route::get('/titles/search', [TitleController::class, 'search']);
Route::get('/titles/detail/{tconst}', [TitleController::class, 'getDetail']);
Route::get('/titles/genre/{genre}', [TitleController::class, 'getByGenre']);
Route::get('/titles/cast/{tconst}', [TitleController::class, 'getCast']);
Route::get('/titles/rating/{tconst}', [TitleController::class, 'getRating']);

// Analytics public
// Route::get('/analytics/top-shows', [AnalyticsController::class, 'topShows']);
// Route::get('/analytics/top-rated', [AnalyticsController::class, 'topRated']);
// Route::get('/analytics/genre-popularity', [AnalyticsController::class, 'genrePopularity']);
// Route::get('/analytics/rating-trend', [AnalyticsController::class, 'ratingTrend']);
// Route::get('/analytics/person-productivity', [AnalyticsController::class, 'personProductivity']);
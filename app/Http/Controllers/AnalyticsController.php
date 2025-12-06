<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function topRated()
    {
        return ['status' => 'ok', 'message' => 'topRated placeholder'];
    }

    public function topShows()
    {
        return ['status' => 'ok', 'message' => 'topShows placeholder'];
    }

    public function genrePopularity()
    {
        return ['status' => 'ok', 'message' => 'genrePopularity placeholder'];
    }

    public function ratingTrend()
    {
        return ['status' => 'ok', 'message' => 'ratingTrend placeholder'];
    }

    public function personProductivity()
    {
        return ['status' => 'ok', 'message' => 'personProductivity placeholder'];
    }
}

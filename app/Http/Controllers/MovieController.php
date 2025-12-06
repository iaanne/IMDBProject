<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        // Untuk sementara tampilkan view kosong supaya tidak error.
        return view('movies.index');
    }
}

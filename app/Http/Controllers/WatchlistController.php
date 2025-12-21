<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WatchlistController extends Controller
{
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $tconst = $request->input('tconst');

        // Cek apakah sudah ada di watchlist
        $exists = Watchlist::where('user_id', $user->id)
                           ->where('tconst', $tconst)
                           ->first();

        if ($exists) {
            // Kalau ada, hapus (Remove)
            $exists->delete();
            return response()->json(['status' => 'removed', 'message' => 'Dihapus dari Watchlist']);
        } else {
            // Kalau belum ada, tambah (Add)
            Watchlist::create([
                'user_id' => $user->id,
                'tconst' => $tconst
            ]);
            return response()->json(['status' => 'added', 'message' => 'Ditambahkan ke Watchlist']);
        }
    }

    public function index()
    {
        $userId = Auth::id();
        
        // 1. Ambil semua ID Film (tconst) dari tabel watchlist user ini
        $watchlistItems = Watchlist::where('user_id', $userId)
                                   ->orderBy('created_at', 'desc')
                                   ->pluck('tconst'); 

        $movies = [];

        // 2. Loop ID tersebut untuk ambil detail film dari SQL Server
        // (Ini cara paling mudah karena databasenya terpisah)
        foreach($watchlistItems as $tconst) {
            $data = DB::select("EXEC sp_GetMovieDetail :tconst", ['tconst' => $tconst]);
            if (!empty($data)) {
                $movies[] = $data[0];
            }
        }

        return view('watchlist.index', compact('movies'));
    }
}
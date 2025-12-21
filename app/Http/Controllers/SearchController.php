<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q'); // Ambil kata kunci dari URL

        // Jika kosong, kembalikan kosong
        if (!$query) {
            return view('search.index', ['results' => [], 'query' => '']);
        }

        // === LOGIKA PENCARIAN CERDAS ===
        $results = DB::table('dim_title as t')
            // Gunakan LEFT JOIN (Jangan JOIN biasa/Inner Join)
            // Agar film baru yang belum ada rating TETAP MUNCUL
            ->leftJoin('fact_title_rating as r', 't.tconst', '=', 'r.tconst')
            
            // Pencarian Judul (Case Insensitive di SQL Server biasanya default)
            ->where('t.primaryTitle', 'LIKE', '%' . $query . '%')
            
            // Opsional: Cari juga berdasarkan nama orang (Cast/Crew)
            ->orWhereIn('t.tconst', function($subquery) use ($query) {
                $subquery->select('tconst')
                    ->from('dim_person_title') // Sesuaikan nama tabel bridge person kamu
                    ->join('dim_person', 'dim_person_title.nconst', '=', 'dim_person.nconst')
                    ->where('dim_person.primaryName', 'LIKE', '%' . $query . '%');
            })

            ->select(
                't.tconst', 
                't.primaryTitle', 
                't.startYear', 
                't.titleType',
                // Kalau rating kosong, anggap 0 atau null
                'r.averageRating',
                'r.numVotes'
            )
            // Urutkan: Paling relevan (Rating tinggi) dulu, lalu Film Baru (Tahun terbaru)
            ->orderByRaw('CASE WHEN r.numVotes IS NULL THEN 1 ELSE 0 END') // Film tanpa rating prioritas kedua
            ->orderBy('r.numVotes', 'DESC') // Film populer di atas
            ->orderBy('t.startYear', 'DESC') // Film baru di atas
            ->limit(50) // Batasi 50 hasil biar gak berat
            ->get();

        return view('search.index', [
            'results' => $results,
            'query' => $query
        ]);
    }
}
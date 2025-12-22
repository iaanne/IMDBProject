<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    /**
     * Mendapatkan daftar film populer untuk ditampilkan di halaman umum.
     *
     * @param int $limit
     * @return array
     */
    public static function getPopularFilms($limit = 12)
    {
        return DB::select('EXEC sp_GetPopularFilms @limit = ?', [$limit]);
    }

    /**
     * Mendapatkan semua daftar genre dari tabel dimensi.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllGenres()
    {
        return DB::table('dim_genre')->orderBy('genre_name')->pluck('genre_name', 'genre_id');
    }
    /**
     * Mendapatkan film dengan filter dan sorting dinamis (dengan paginasi).
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getFilteredFilms($filters = [])
    {
        $query = DB::table('dim_title AS dt')
            ->join('fact_title_rating AS ftr', 'dt.tconst', '=', 'ftr.tconst')
            ->select(
                'dt.tconst',
                'dt.primaryTitle',
                'dt.startYear',
                'ftr.averageRating',
                'ftr.numVotes',
                'dt.runtimeMinutes'
            )
            ->where('dt.titleType', 'movie')
            ->whereNotNull('dt.startYear')
            ->where('dt.startYear', '<=', now()->year)
            ->whereNotNull('ftr.averageRating')
            ->where('ftr.numVotes', '>', 1000);

        // Filter berdasarkan genre (jika dipilih)
        if (!empty($filters['genre_id'])) {
            $query->join('bridge_title_genre AS btg', 'dt.tconst', '=', 'btg.tconst')
                ->where('btg.genre_id', $filters['genre_id']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'rating_desc'; // Default sorting
        switch ($sortBy) {
            case 'year_asc':
                $query->orderBy('dt.startYear', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('dt.startYear', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('dt.primaryTitle', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('dt.primaryTitle', 'desc');
                break;
            case 'rating_desc':
            default:
                $query->orderBy('ftr.averageRating', 'desc')
                    ->orderBy('ftr.numVotes', 'desc');
                break;
        }

        // Kembalikan hasil yang sudah dipaginasi, 12 item per halaman
        return $query->paginate(12);
    }
}

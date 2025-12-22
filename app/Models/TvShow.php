<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TvShow extends Model
{
    use HasFactory;

    /**
     * Mendapatkan daftar TV Show populer.
     *
     * @param int $limit
     * @return array
     */
    public static function getPopularShows($limit = 8)
    {
        return DB::select('EXEC sp_GetPopularTvShows @limit = ?', [$limit]);
    }

    /**
     * Mendapatkan semua daftar network TV.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllNetworks()
    {
        // DB::select mengembalikan array, kita ubah ke koleksi untuk konsistensi
        $networks = DB::select('EXEC sp_GetAllTvNetworks');
        return collect($networks);
    }

    /**
     * Mendapatkan TV Show dengan filter dan sorting dinamis.
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public static function getFilteredShows($filters = [])
    {
        $query = DB::table('dim_show AS ds')
            ->join('fact_show_vote AS fsv', 'ds.show_id', '=', 'fsv.show_id')
            ->select(
                'ds.show_id',
                'ds.name AS primaryTitle',
                'ds.number_of_seasons',
                'ds.overview',
                'fsv.vote_average AS averageRating',
                'fsv.vote_count AS numVotes'
            )
            ->where('ds.number_of_seasons', '>', 0)
            ->whereNotNull('fsv.vote_average')
            ->where('fsv.vote_count', '>', 50);

        // Filter berdasarkan network (jika dipilih)
        if (!empty($filters['network_id'])) {
            $query->join('bridge_show_network AS bsn', 'ds.show_id', '=', 'bsn.show_id')
                ->where('bsn.network_type_id', $filters['network_id']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'rating_desc'; // Default sorting
        switch ($sortBy) {
            case 'season_asc':
                $query->orderBy('ds.number_of_seasons', 'asc');
                break;
            case 'season_desc':
                $query->orderBy('ds.number_of_seasons', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('ds.name', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('ds.name', 'desc');
                break;
            case 'rating_desc':
            default:
                $query->orderBy('fsv.vote_average', 'desc')
                    ->orderBy('fsv.vote_count', 'desc');
                break;
        }

        return $query->get();
    }
}

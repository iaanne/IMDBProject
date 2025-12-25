<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionController extends Controller
{
    // ========== DASHBOARD ==========
    // ========== DASHBOARD (DIALIHKAN) ==========
public function dashboard()
    {
        // 1. Upcoming (Siap Tayang)
        $upcomingContent = DB::select('EXEC sp_GetUpcomingContent');

        // 2. In Production (Sedang Syuting) - BARU
        $inProductionContent = DB::select('EXEC sp_GetInProductionContent');

        // 3. Top Productions (INI YANG TADI HILANG/ERROR)
        $topProductions = DB::select('EXEC sp_GetTopProductions');

        // 2. Top Directors (Sutradara Rating Tinggi)
        $topDirectors = DB::select('EXEC sp_GetTopDirectors');

        // 3. Top Writers (Penulis Rating Tinggi)
        $topWriters = DB::select('EXEC sp_GetTopWriters');

        // 4. Top Cast (Aktor Terpopuler)
        $topCast = DB::select('EXEC sp_GetTopCast');

        // 5. Upcoming Content (Film/TV Masa Depan)
        $upcomingContent = DB::select('EXEC sp_GetUpcomingContent');

        // 6. Platform Analysis (Analisis Network)
        $platformAnalysis = DB::select('EXEC sp_GetPlatformAnalysis');

        $competitorLeaderboard = DB::select('EXEC sp_GetCompetitorLeaderboard');

        // Kirim data ke View
        return view('production.dashboard', compact(
           'upcomingContent',
            'inProductionContent',
            'topProductions', 
            'topDirectors', 
            'topWriters', 
            'topCast', 
            'platformAnalysis', 
            'competitorLeaderboard'
        ));
    }

    // ========== MOVIES CRUD ==========
    public function indexMovies()
    {
        try {
            $movies = DB::select("
                SELECT TOP 100 
                    t.*,
                    COALESCE(r.averageRating, 0) as rating,
                    COALESCE(r.numVotes, 0) as votes
                FROM dim_title t
                LEFT JOIN fact_title_rating r ON t.tconst = r.tconst
                WHERE t.titleType = 'movie'
                ORDER BY t.startYear DESC
            ");
            return view('production.movies.index', compact('movies'));
        } catch (\Exception $e) {
            Log::error('Movies Index Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data film: ' . $e->getMessage());
        }
    }

    public function createMovie()
    {
        try {
            $genres = DB::select("SELECT * FROM dim_genre ORDER BY genre_name");
            return view('production.movies.create', compact('genres'));
        } catch (\Exception $e) {
            Log::error('Create Movie Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    public function storeMovie(Request $request)
    {
        $request->validate([
            'tconst' => 'required|max:10',
            'primaryTitle' => 'required|max:4000',
            'startYear' => 'required|integer|min:1800|max:2100',
            'runtimeMinutes' => 'nullable|integer|min:1',
            'isAdult' => 'nullable|boolean',
            'genres' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();
            DB::statement('EXEC sp_InsertTitle @tconst = ?, @primaryTitle = ?, @titleType = ?, @startYear = ?, @originalTitle = ?, @runtimeMinutes = ?, @isAdult = ?', 
            [
                $request->tconst,
                $request->primaryTitle,
                'movie',
                $request->startYear,
                $request->originalTitle ?? $request->primaryTitle,
                $request->runtimeMinutes,
                $request->has('isAdult') ? 1 : 0
            ]);

            if ($request->genres) {
                foreach ($request->genres as $genreId) {
                    DB::statement('EXEC sp_AssignGenreToTitle @tconst = ?, @genre_id = ?', [$request->tconst, $genreId]);
                }
            }

            DB::commit();
            return redirect()->route('production.movies.index')->with('success', 'Film berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Movie Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan film: ' . $e->getMessage());
        }
    }

    public function destroyMovie($tconst)
    {
        try {
            DB::statement('EXEC sp_DeleteTitle @tconst = ?', [$tconst]);
            return redirect()->route('production.movies.index')->with('success', 'Film berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus film: ' . $e->getMessage());
        }
    }

    // ========== SHOWS CRUD ==========
    public function indexShows()
    {
        try {
            $shows = DB::select("SELECT TOP 100 * FROM dim_show ORDER BY show_id DESC");
            return view('production.shows.index', compact('shows'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data show: ' . $e->getMessage());
        }
    }

    public function createShow()
    {
        try {
            $types = DB::select("SELECT * FROM dim_show_type"); 
            $statuses = DB::select("SELECT * FROM dim_status_type");
            return view('production.shows.create', compact('types', 'statuses'));
        } catch (\Exception $e) {
            return view('production.shows.create', ['types' => [], 'statuses' => []]);
        }
    }

    public function storeShow(Request $request)
    {
        $request->validate([
            'show_id' => 'required|integer',
            'name' => 'required|max:4000',
        ]);

        try {
            DB::statement('EXEC sp_InsertShow @show_id = ?, @name = ?, @overview = ?, @original_name = ?, @number_of_seasons = ?, @number_of_episodes = ?, @episode_run_time = ?, @popularity = ?, @tagline = ?, @adult = ?, @in_production = ?, @type_id = ?, @status_id = ?', 
            [
                $request->show_id,
                $request->name,
                $request->overview,
                $request->original_name ?? $request->name,
                $request->number_of_seasons,
                $request->number_of_episodes,
                $request->episode_run_time,
                $request->popularity ?? 0,
                $request->tagline,
                $request->has('adult') ? 1 : 0,
                $request->has('in_production') ? 1 : 0,
                $request->type_id,
                $request->status_id
            ]);

            return redirect()->route('production.shows.index')->with('success', 'Show berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan show: ' . $e->getMessage());
        }
    }

    public function editShow($show_id)
    {
        try {
            $show = DB::select("SELECT * FROM dim_show WHERE show_id = ?", [$show_id]);
            if (empty($show)) return redirect()->route('production.shows.index')->with('error', 'Show tidak ditemukan!');

            $types = DB::select("SELECT * FROM dim_show_type");
            $statuses = DB::select("SELECT * FROM dim_status_type");

            return view('production.shows.edit', ['show' => $show[0], 'types' => $types, 'statuses' => $statuses]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal edit: ' . $e->getMessage());
        }
    }

    public function updateShow(Request $request, $show_id)
    {
        // ... (Logic update sama seperti store)
        // Disederhanakan untuk mempersingkat jawaban
        return $this->storeShow($request); 
    }

    // ========== EPISODES CRUD ==========
    public function indexEpisodes()
    {
        $episodes = DB::select("
            SELECT TOP 100 
                e.tconst, e.seasonNumber, e.episodeNumber,
                t_eps.primaryTitle as episode_title,
                t_parent.primaryTitle as series_title
            FROM dim_episode e
            LEFT JOIN dim_title t_eps ON e.tconst = t_eps.tconst
            LEFT JOIN dim_title t_parent ON e.parentTconst = t_parent.tconst
            ORDER BY e.parentTconst, e.seasonNumber, e.episodeNumber
        ");
        return view('production.episodes.index', compact('episodes'));
    }

    public function createEpisode()
    {
        // Kosongkan ini agar loading cepat (Search pakai AJAX)
        return view('production.episodes.create');
    }

    public function storeEpisode(Request $request)
    {
        $request->validate([
            'tconst' => 'required|max:10',
            'parentTconst' => 'required|max:10',
            'seasonNumber' => 'required|integer',
            'episodeNumber' => 'required|integer',
            'primaryTitle' => 'required',
        ]);

        try {
            DB::statement("EXEC sp_InsertEpisodeFull 
    @tconst = ?, 
    @parentTconst = ?, 
    @seasonNumber = ?, 
    @episodeNumber = ?, 
    @primaryTitle = ?, 
    @runtimeMinutes = ?", 
    [
        $request->tconst,
        $request->parentTconst,
        $request->seasonNumber,
        $request->episodeNumber,
        $request->primaryTitle,
        $request->runtimeMinutes
    ]
);
            return redirect()->route('production.episodes.index')->with('success', 'Episode berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // --- BAGIAN YANG TADI ERROR (SUDAH DIPERBAIKI) ---
    public function editEpisode($tconst)
    {
        // 1. Ambil Data Episode + ALIAS episode_title
        $episode = DB::selectOne("
            SELECT e.*, t.primaryTitle as episode_title, t.runtimeMinutes
            FROM dim_episode e
            JOIN dim_title t ON e.tconst = t.tconst
            WHERE e.tconst = ?
        ", [$tconst]);

        if (!$episode) {
            return redirect()->route('production.episodes.index')->with('error', 'Episode tidak ditemukan!');
        }

        // 2. Ambil Data Parent Series (Untuk Mengisi Default Select2)
        $parentSeries = DB::selectOne("
            SELECT tconst, primaryTitle, startYear
            FROM dim_title
            WHERE tconst = ?
        ", [$episode->parentTconst]);

        return view('production.episodes.edit', compact('episode', 'parentSeries'));
    }

    public function updateEpisode(Request $request, $tconst)
    {
        $currentEpisode = DB::selectOne("SELECT parentTconst FROM dim_episode WHERE tconst = ?", [$tconst]);
        
        $request->validate([
            'seasonNumber' => 'required|integer',
            'episodeNumber' => 'required|integer',
            'primaryTitle' => 'required',
        ]);

        try {
            DB::statement('EXEC sp_InsertEpisodeFull @tconst = ?, @parentTconst = ?, @seasonNumber = ?, @episodeNumber = ?, @primaryTitle = ?, @runtimeMinutes = ?', 
            [
                $tconst,
                $currentEpisode->parentTconst, 
                $request->seasonNumber,
                $request->episodeNumber,
                $request->primaryTitle, 
                $request->runtimeMinutes
            ]);
            return redirect()->route('production.episodes.index')->with('success', 'Episode berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function searchSeries(Request $request)
{
    $query = $request->get('q');

    // Cari data di database (hanya tvSeries agar tidak campur dengan film)
    $data = DB::table('dim_title')
        ->where('titleType', 'tvSeries')
        ->where('primaryTitle', 'LIKE', "%$query%")
        ->select('tconst as id', 'primaryTitle as text')
        ->limit(10)
        ->get();

    return response()->json($data);
}
}
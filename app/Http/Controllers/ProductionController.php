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
        $movies = DB::select('EXEC sp_GetMovies_HBO @Top = ?', [100]);
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

public function indexShows()
{
    try {
        $shows = DB::select('EXEC sp_GetShows_HBO @Top = ?', [100]);
        return view('production.shows.index', compact('shows'));
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal memuat data show: ' . $e->getMessage());
    }
}

public function createShow()
{
    try {
        $types = DB::select('EXEC sp_GetShowTypes');
        $statuses = DB::select('EXEC sp_GetStatusTypes');
        return view('production.shows.create', compact('types', 'statuses'));
    } catch (\Exception $e) {
        return view('production.shows.create', ['types' => [], 'statuses' => []]);
    }
}

public function editShow($show_id)
{
    try {
        // HBO-only (show yang bukan HBO gak bisa diedit)
        $show = DB::selectOne('EXEC dbo.sp_GetShowById_HBO @show_id = ?', [$show_id]);
        if (!$show) {
            return redirect()->route('production.shows.index')
                ->with('error', 'Show tidak ditemukan / bukan HBO!');
        }

        // Master data: ga perlu SP (biar ga error missing SP)
        $types = DB::select("SELECT * FROM dim_show_type");
        $statuses = DB::select("SELECT * FROM dim_status_type");

        return view('production.shows.edit', compact('show', 'types', 'statuses'));
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal edit: ' . $e->getMessage());
    }
}


public function updateShow(Request $request, $show_id)
{
    $request->validate([
        'name' => 'required|max:4000',
    ]);

    try {
        DB::statement('EXEC sp_UpdateShow_HBO 
            @show_id = ?, 
            @name = ?, 
            @overview = ?, 
            @original_name = ?, 
            @number_of_seasons = ?, 
            @number_of_episodes = ?, 
            @episode_run_time = ?, 
            @popularity = ?, 
            @tagline = ?, 
            @adult = ?, 
            @in_production = ?, 
            @type_id = ?, 
            @status_id = ?',
        [
            $show_id,
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

        return redirect()->route('production.shows.index')->with('success', 'Show berhasil diupdate!');
    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Gagal update show: ' . $e->getMessage());
    }
}


public function indexEpisodes()
{
    $episodes = DB::select('EXEC sp_GetEpisodes_HBO @Top = ?', [100]);
    return view('production.episodes.index', compact('episodes'));
}

public function editEpisode($tconst)
{
    $episode = DB::selectOne('EXEC sp_GetEpisodeForEdit_HBO @tconst = ?', [$tconst]);

    if (!$episode) {
        return redirect()->route('production.episodes.index')->with('error', 'Episode tidak ditemukan / bukan HBO!');
    }

    $parentSeries = DB::selectOne('EXEC sp_GetParentSeries_HBO @tconst = ?', [$episode->parentTconst]);

    return view('production.episodes.edit', compact('episode', 'parentSeries'));
}

public function createEpisode()
{

    return view('production.episodes.create');
}

public function storeEpisode(Request $request)
{
    $request->validate([
        'tconst' => 'required|max:10',
        'parentTconst' => 'required|max:10',
        'seasonNumber' => 'required|integer|min:1',
        'episodeNumber' => 'required|integer|min:1',
        'primaryTitle' => 'required|max:400',
        'runtimeMinutes' => 'nullable|integer|min:1',
        'overview' => 'nullable|string',
    ]);

    try {
        DB::statement(
            'EXEC sp_InsertEpisode @tconst = ?, @parentTconst = ?, @seasonNumber = ?, @episodeNumber = ?, @primaryTitle = ?, @runtimeMinutes = ?, @overview = ?',
            [
                $request->tconst,
                $request->parentTconst,
                $request->seasonNumber,
                $request->episodeNumber,
                $request->primaryTitle,
                $request->runtimeMinutes,
                $request->overview
            ]
        );

        return redirect()->route('production.episodes.index')->with('success', 'Episode berhasil ditambahkan!');
    } catch (\Exception $e) {
        Log::error('Store Episode Error: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Gagal menambahkan episode: ' . $e->getMessage());
    }
}

public function updateEpisode(Request $request, $tconst)
{
    $request->validate([
        'parentTconst' => 'required|max:10',
        'seasonNumber' => 'required|integer|min:1',
        'episodeNumber' => 'required|integer|min:1',
        'primaryTitle' => 'required|max:400',
        'runtimeMinutes' => 'nullable|integer|min:1',
        'overview' => 'nullable|string',
    ]);

    try {
        DB::statement(
            'EXEC sp_InsertEpisode @tconst = ?, @parentTconst = ?, @seasonNumber = ?, @episodeNumber = ?, @primaryTitle = ?, @runtimeMinutes = ?, @overview = ?',
            [
                $tconst,
                $request->parentTconst,
                $request->seasonNumber,
                $request->episodeNumber,
                $request->primaryTitle,
                $request->runtimeMinutes,
                $request->overview
            ]
        );

        return redirect()->route('production.episodes.index')->with('success', 'Episode berhasil diupdate!');
    } catch (\Exception $e) {
        Log::error('Update Episode Error: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Gagal update episode: ' . $e->getMessage());
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
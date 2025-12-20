<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionController extends Controller
{
    // ========== DASHBOARD ==========
    public function dashboard()
    {
        try {
            $totalMovies = DB::select("SELECT COUNT(*) as total FROM dim_title WHERE titleType = 'movie'")[0]->total ?? 0;
            $totalTVSeries = DB::select("SELECT COUNT(*) as total FROM dim_title WHERE titleType = 'tvSeries'")[0]->total ?? 0;
            $totalShows = DB::select("SELECT COUNT(*) as total FROM dim_show")[0]->total ?? 0;
            $totalEpisodes = DB::select("SELECT COUNT(*) as total FROM dim_episode")[0]->total ?? 0;
            
            // Recent additions
            $recentMovies = DB::select("SELECT TOP 5 * FROM dim_title WHERE titleType = 'movie' ORDER BY startYear DESC");
            $recentShows = DB::select("SELECT TOP 5 * FROM dim_show ORDER BY show_id DESC");
            
            return view('production.dashboard', compact(
                'totalMovies', 
                'totalTVSeries', 
                'totalShows', 
                'totalEpisodes',
                'recentMovies',
                'recentShows'
            ));
        } catch (\Exception $e) {
            Log::error('Production Dashboard Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }

    // ========== MOVIES CRUD ==========
    public function indexMovies()
    {

        $path = resource_path('views/production/movies/index.blade.php');
        
        // Kita paksa cek fisik dulu sebelum Laravel melakukan apapun
    
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
            // Ambil daftar genre untuk dropdown
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
            'genres' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();
            
            // Insert title
            DB::statement('EXEC sp_InsertTitle @tconst = ?, @primaryTitle = ?, @titleType = ?, @startYear = ?', [
                $request->tconst,
                $request->primaryTitle,
                'movie',
                $request->startYear
            ]);

            // Assign genres if selected
            if ($request->genres) {
                foreach ($request->genres as $genreId) {
                    DB::statement('EXEC sp_AssignGenreToTitle @tconst = ?, @genre_id = ?', [
                        $request->tconst,
                        $genreId
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('production.movies.index')
                ->with('success', 'Film berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Movie Error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal menambahkan film: ' . $e->getMessage());
        }
    }

    public function destroyMovie($tconst)
    {
        try {
            DB::statement('EXEC sp_DeleteTitle @tconst = ?', [$tconst]);
            return redirect()->route('production.movies.index')
                ->with('success', 'Film berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Delete Movie Error: ' . $e->getMessage());
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
            Log::error('Shows Index Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data show: ' . $e->getMessage());
        }
    }

    public function createShow()
    {
        return view('production.shows.create');
    }

    public function storeShow(Request $request)
    {
        $request->validate([
            'show_id' => 'required|integer',
            'name' => 'required|max:4000',
            'overview' => 'nullable|max:4000'
        ]);

        try {
            DB::statement('EXEC sp_InsertShow @show_id = ?, @name = ?, @overview = ?', [
                $request->show_id,
                $request->name,
                $request->overview ?? ''
            ]);

            return redirect()->route('production.shows.index')
                ->with('success', 'Show berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Store Show Error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal menambahkan show: ' . $e->getMessage());
        }
    }

    public function editShow($show_id)
    {
        try {
            $show = DB::select("SELECT * FROM dim_show WHERE show_id = ?", [$show_id]);
            if (empty($show)) {
                return redirect()->route('production.shows.index')
                    ->with('error', 'Show tidak ditemukan!');
            }
            return view('production.shows.edit', ['show' => $show[0]]);
        } catch (\Exception $e) {
            Log::error('Edit Show Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuka form edit: ' . $e->getMessage());
        }
    }

    public function updateShow(Request $request, $show_id)
    {
        $request->validate([
            'name' => 'required|max:4000'
        ]);

        try {
            DB::statement('EXEC sp_UpdateShow @show_id = ?, @name = ?', [
                $show_id,
                $request->name
            ]);

            return redirect()->route('production.shows.index')
                ->with('success', 'Show berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Update Show Error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal mengupdate show: ' . $e->getMessage());
        }
    }

    // ========== EPISODES CRUD ==========
    public function indexEpisodes()
    {
        try {
            $episodes = DB::select("
                SELECT TOP 100 
                    e.*,
                    t.primaryTitle as episode_title,
                    p.primaryTitle as series_title
                FROM dim_episode e
                LEFT JOIN dim_title t ON e.tconst = t.tconst
                LEFT JOIN dim_title p ON e.parentTconst = p.tconst
                ORDER BY e.tconst DESC
            ");
            return view('production.episodes.index', compact('episodes'));
        } catch (\Exception $e) {
            Log::error('Episodes Index Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data episode: ' . $e->getMessage());
        }
    }

    public function createEpisode()
    {
        try {
            // Ambil daftar TV Series untuk parent dropdown
            $tvSeries = DB::select("SELECT tconst, primaryTitle FROM dim_title WHERE titleType = 'tvSeries' ORDER BY primaryTitle");
            return view('production.episodes.create', compact('tvSeries'));
        } catch (\Exception $e) {
            Log::error('Create Episode Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    public function storeEpisode(Request $request)
    {
        $request->validate([
            'tconst' => 'required|max:10',
            'parentTconst' => 'required|max:10',
            'seasonNumber' => 'required|integer|min:1',
            'episodeNumber' => 'required|integer|min:1'
        ]);

        try {
            DB::statement('EXEC sp_InsertEpisode @tconst = ?, @parent = ?, @season = ?, @episode = ?', [
                $request->tconst,
                $request->parentTconst,
                $request->seasonNumber,
                $request->episodeNumber
            ]);

            return redirect()->route('production.episodes.index')
                ->with('success', 'Episode berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Store Episode Error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal menambahkan episode: ' . $e->getMessage());
        }
    }

    public function editEpisode($tconst)
    {
        try {
            $episode = DB::select("
                SELECT e.*, t.primaryTitle as episode_title
                FROM dim_episode e
                LEFT JOIN dim_title t ON e.tconst = t.tconst
                WHERE e.tconst = ?
            ", [$tconst]);
            
            if (empty($episode)) {
                return redirect()->route('production.episodes.index')
                    ->with('error', 'Episode tidak ditemukan!');
            }
            
            return view('production.episodes.edit', ['episode' => $episode[0]]);
        } catch (\Exception $e) {
            Log::error('Edit Episode Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuka form edit: ' . $e->getMessage());
        }
    }

    public function updateEpisode(Request $request, $tconst)
    {
        $request->validate([
            'seasonNumber' => 'required|integer|min:1',
            'episodeNumber' => 'required|integer|min:1'
        ]);

        try {
            DB::statement('EXEC sp_UpdateEpisode @tconst = ?, @season = ?, @episode = ?', [
                $tconst,
                $request->seasonNumber,
                $request->episodeNumber
            ]);

            return redirect()->route('production.episodes.index')
                ->with('success', 'Episode berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Update Episode Error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal mengupdate episode: ' . $e->getMessage());
        }
    }
}
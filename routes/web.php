<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TitleController;

// Guest routes (accessible without login)
Route::get('/', [TitleController::class, 'home'])->name('home');
Route::get('/search', [TitleController::class, 'search'])->name('titles.search');
Route::get('/title/{tconst}', [TitleController::class, 'show'])->name('titles.show');
Route::get('/genre/{genre}', [TitleController::class, 'byGenre'])->name('titles.byGenre');

// Route untuk testing pure SQL (bypass controller)
Route::get('/search-test', function(Request $request) {
    $keyword = $request->input('q', 'test');
    
    try {
        // Coba langsung tanpa controller
        $results = DB::select('SELECT TOP 5 * FROM dim_title WHERE primaryTitle LIKE ?', ['%' . $keyword . '%']);
        
        return response()->json([
            'success' => true,
            'keyword' => $keyword,
            'count' => count($results),
            'results' => $results
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'keyword' => $keyword
        ], 500);
    }
});

Route::get('/test-title/{tconst}', function($tconst) {
    echo "<pre>";
    echo "=== TESTING TITLE: {$tconst} ===\n\n";
    
    try {
        // 1. Test dim_title
        echo "1. Checking dim_title table...\n";
        $title = DB::table('dim_title')->where('tconst', $tconst)->first();
        
        if ($title) {
            echo "   ✓ Title found!\n";
            echo "   - Primary Title: {$title->primaryTitle}\n";
            echo "   - Type: {$title->titleType}\n";
            echo "   - Year: {$title->startYear}\n";
        } else {
            echo "   ✗ Title NOT found in dim_title\n";
        }
        
        // 2. Test fact_title_rating
        echo "\n2. Checking fact_title_rating...\n";
        $rating = DB::table('fact_title_rating')->where('tconst', $tconst)->first();
        if ($rating) {
            echo "   ✓ Rating found: {$rating->averageRating} ({$rating->numVotes} votes)\n";
        } else {
            echo "   ✗ No rating found\n";
        }
        
        // 3. Test bridge_title_genre
        echo "\n3. Checking genres...\n";
        $genres = DB::table('bridge_title_genre as btg')
            ->join('dim_genre as dg', 'btg.genre_id', '=', 'dg.genre_id')
            ->where('btg.tconst', $tconst)
            ->select('dg.genre_name')
            ->get();
        
        if ($genres->count() > 0) {
            echo "   ✓ Genres found: " . $genres->count() . "\n";
            foreach ($genres as $genre) {
                echo "   - {$genre->genre_name}\n";
            }
        } else {
            echo "   ✗ No genres found\n";
        }
        
        // 4. Test bridge_title_principal
        echo "\n4. Checking cast...\n";
        $cast = DB::table('bridge_title_principal as btp')
            ->join('dim_person as dp', 'btp.nconst', '=', 'dp.nconst')
            ->join('dim_principal_category as dpc', 'btp.category_id', '=', 'dpc.category_id')
            ->where('btp.tconst', $tconst)
            ->select('dp.primaryName', 'dpc.category_name')
            ->limit(5)
            ->get();
        
        if ($cast->count() > 0) {
            echo "   ✓ Cast found: " . $cast->count() . " people\n";
            foreach ($cast as $person) {
                echo "   - {$person->primaryName} ({$person->category_name})\n";
            }
        } else {
            echo "   ✗ No cast found\n";
        }
        
        // 5. Test stored procedure
        echo "\n5. Testing stored procedure sp_Title_GetDetail...\n";
        try {
            $spDetail = DB::select("EXEC sp_Title_GetDetail ?", [$tconst]);
            echo "   ✓ Stored procedure works. Results: " . count($spDetail) . "\n";
            if (count($spDetail) > 0) {
                print_r($spDetail[0]);
            }
        } catch (\Exception $e) {
            echo "   ✗ Stored procedure error: " . $e->getMessage() . "\n";
        }
        
    } catch (\Exception $e) {
        echo "✗ General error: " . $e->getMessage() . "\n";
        echo "\nStack trace:\n";
        echo $e->getTraceAsString();
    }
    
    echo "\n=== TEST COMPLETE ===\n";
    echo "</pre>";
    
    echo '<p><a href="/title/' . $tconst . '">Go to Detail Page</a></p>';
    echo '<p><a href="/search">Back to Search</a></p>';
});
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $role = $user ? $user->role : 'guest';
        
        // Map application role to database connection
        $dbConnection = match($role) {
            'executive' => 'sqlsrv_executive',
            'production' => 'sqlsrv_production',
            default => 'sqlsrv'
        };

        // Set the database connection for this request
        config(['database.default' => $dbConnection]);
        
        // Optional: Test connection
        try {
            DB::connection($dbConnection)->getPdo();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database connection failed',
                'message' => $e->getMessage(),
                'connection' => $dbConnection
            ], 500);
        }

        return $next($request);
    }
}
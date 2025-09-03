<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Health Check Routes
|--------------------------------------------------------------------------
|
| These routes are used for health checks and monitoring
|
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
        'environment' => app()->environment(),
    ]);
});

Route::get('/health/db', function () {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'timestamp' => now()->toISOString(),
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'database' => 'disconnected',
            'error' => $e->getMessage(),
            'timestamp' => now()->toISOString(),
        ], 500);
    }
});

Route::get('/health/cache', function () {
    try {
        $key = 'health_check_' . time();
        Cache::put($key, 'test', 60);
        $value = Cache::get($key);
        Cache::forget($key);
        
        if ($value === 'test') {
            return response()->json([
                'status' => 'healthy',
                'cache' => 'working',
                'timestamp' => now()->toISOString(),
            ]);
        } else {
            throw new Exception('Cache test failed');
        }
    } catch (Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'cache' => 'not working',
            'error' => $e->getMessage(),
            'timestamp' => now()->toISOString(),
        ], 500);
    }
});

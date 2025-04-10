<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Check whether the database test status has been recorded in the cache
        if (!Cache::has('db_tested')) {
            try {
                DB::connection()->getPdo(); // Test database connection
                Log::info('Database connection test successful！');
                Cache::put('db_tested', true, 3600); // The symbol is valid 1 hour
            } catch (\Exception $e) {
                Log::error('Failed database connection test：' . $e->getMessage());
            }
        }
    }
}

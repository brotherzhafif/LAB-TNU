<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\CommonMark\Environment\Environment;

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
        // Force HTTPS in production
        if (config('app.url') == 'https://lab-tnu.vercel.app/') {
            \URL::forceScheme('https');

            config([
                'excel.exports.temp_path' => '/tmp',
                'filesystems.disks.local.root' => '/tmp',
                'view.compiled' => '/tmp',
                'cache.stores.file.path' => '/tmp',
            ]);
        }
    }
}

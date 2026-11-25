<?php

namespace ExtensionsValley\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadViewsFrom(__DIR__ . '/Views', 'Dashboard');

        /*$this->publishes([
            __DIR__ . '/views' => base_path('resources/views/extensionsvalley/Dashboard'),
        */

        $this->publishes([
            __DIR__ . '/../public' => public_path('packages/extensionsvalley/dashboard'),
        ]);

        $this->publishes([
            __DIR__ . '/Database/migrations' => $this->app->databasePath() . '/migrations',
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/Database/seeds' => $this->app->databasePath() . '/seeds',
        ], 'seeds');

        // Load all routes in boot method for Laravel 11
        $this->loadRoutesFrom(__DIR__ . '/Routes/user.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/dashboard.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/group.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/extensions.php');
        
        // Catching up the events
        foreach (new \DirectoryIterator(__DIR__ . '/Events/') as $fileInfo) {
            if (!$fileInfo->isDot()) {
                include __DIR__ . '/Events/' . $fileInfo->getFilename();
            }
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

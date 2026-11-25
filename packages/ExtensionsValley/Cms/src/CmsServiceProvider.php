<?php

namespace ExtensionsValley\Cms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'Cms');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        Blade::directive('shortcode', function ($expression) {
            return "<?php echo \\ExtensionsValley\\Cms\\Helpers\\ShortcodeHelper::render({$expression}); ?>";
        });

        $this->publishes([
            __DIR__ . '/Database/migrations' => $this->app->databasePath('migrations'),
        ], 'cms-migrations');

        $this->loadRoutesFrom(__DIR__ . '/Routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/frontend.php');

        if (is_dir(__DIR__ . '/Events')) {
            foreach (new \DirectoryIterator(__DIR__ . '/Events') as $fileInfo) {
                if (!$fileInfo->isDot() && $fileInfo->isFile()) {
                    include __DIR__ . '/Events/' . $fileInfo->getFilename();
                }
            }
        }
    }

    public function register(): void
    {
        //
    }
}


<?php

namespace kluvi\AdminerAdmin\Base;

use Illuminate\Support\Facades\Route;
use kluvi\AdminerAdmin\Commands\DownloadCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/adminer-admin.php' => config_path('adminer-admin.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/migrations/2017_07_03_003842_create_adminer_users_table.php' => database_path('migrations/create_adminer_users_table.php'),
            __DIR__ . '/migrations/2017_07_03_003842_create_adminer_help_table.php' => database_path('migrations/create_adminer_help_table.php'),
        ], 'migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                DownloadCommand::class,
            ]);
        }

//        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/adminer-admin.php', 'adminer-admin'
        );

        $route = config('adminer-admin.route');
        if (strlen($route) > 0) {
            Route::any($route, [Controller::class,'index'])->name('adminer-admin');
            Route::any($route.'/upload-file', [Controller::class,'upload'])->name('adminer-admin-upload-file');
        }
    }
}
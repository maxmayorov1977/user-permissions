<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserPermissionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');



            $this->publishes([
                __DIR__ . '/../../config/user-permission.php' => config_path('user-permission.php'),
            ]);
        }
    }
}

<?php

namespace App\Providers;

use App\Models\Status;
use App\Services\TaskService;
use App\Services\ValidationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ValidationService::class, function (Application $app) {
            return new ValidationService(
                $app->make(ValidationFactory::class),
            );
        });
        $this->app->bind(TaskService::class, function (Application $app) {
            return new TaskService(
                $app->make(Status::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

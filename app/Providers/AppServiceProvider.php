<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Share course and lesson data with all views for theming
        view()->composer('*', function ($view) {
            $route = request()->route();

            if ($route) {
                // Check if course is in route parameters
                if ($route->hasParameter('course')) {
                    $course = $route->parameter('course');
                    $view->with('course', $course);
                }

                // Check if lesson is in route parameters
                if ($route->hasParameter('lesson')) {
                    $lesson = $route->parameter('lesson');
                    $view->with('lesson', $lesson);
                }
            }
        });
    }
}

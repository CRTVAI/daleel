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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer('components.onboarding-modal', function ($view) {
            $view->with([
                'interestsList' => \App\Models\Content::distinct()->pluck('category')->filter()->values(),
                'learningGoals' => \Illuminate\Support\Facades\DB::table('learning_goals')->get(),
                'experienceLevels' => \Illuminate\Support\Facades\DB::table('experience_levels')->get(),
                'tools' => \App\Models\Tool::where('status', 'active')->orderBy('name')->get(),
            ]);
        });
    }
}

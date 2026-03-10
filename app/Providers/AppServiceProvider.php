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
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $reminders = \App\Models\Note::where('user_id', auth()->id())
                    ->whereDate('date', \Carbon\Carbon::today())
                    ->whereNotNull('reminder_time')
                    ->orderBy('reminder_time')
                    ->get();
                $view->with('todayReminders', $reminders);
            }
        });
    }
}

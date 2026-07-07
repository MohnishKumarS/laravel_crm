<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
         View::composer('*', function ($view) {

        $notifications = collect();
        $notificationCount = 0;

        if (Auth::check()) {
            $notifications = Auth::user()
                ->unreadNotifications()
                ->latest()
                ->take(5)
                ->get();

            $notificationCount = Auth::user()
                ->unreadNotifications()
                ->count();
        }

        $view->with([
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
        ]);
    });
    }
}

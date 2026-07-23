<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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
        // NOTIFICATIONS
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

        // ROLE BASE
        Blade::if('role', function ($roles) {

            if (!Auth::check()) {
                return false;
            }

            $roles = is_array($roles) ? $roles : [$roles];

            return in_array(Auth::user()->role, $roles);
        });
    }
}

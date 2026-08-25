<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
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
        // $this->app->singleton('settings', function () {
        //     return cache()->rememberForever('settings', function () {
        //         return Setting::first();
        //     });
        // });
        // $settings = app('settings');
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

            $partnerMessages = collect();
            $partnerCount = 0;

            if (Auth::check()) {
                // Bell
                $notifications = Auth::user()
                    ->unreadNotifications()
                    ->where('data->type', '!=', 'partner_chat')
                    ->latest()
                    ->take(5)
                    ->get();

                $notificationCount = Auth::user()
                    ->unreadNotifications()
                    ->where('data->type', '!=', 'partner_chat')
                    ->count();

                // Messages
                $partnerMessages = Auth::user()
                    ->unreadNotifications()
                    ->where('data->type', 'partner_chat')
                    ->latest()
                    ->take(10)
                    ->get();
                $partnerCount = Auth::user()
                    ->unreadNotifications()
                    ->where('data->type', 'partner_chat')
                    ->count();
            }

            $view->with([
                'notifications' => $notifications,
                'notificationCount' => $notificationCount,
                'partnerMessages' => $partnerMessages,
                'partnerCount' => $partnerCount,

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

        // Bootstrap Paginate
        Paginator::useBootstrapFive();
    }
}

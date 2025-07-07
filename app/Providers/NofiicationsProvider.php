<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NofiicationsProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Gửi thông báo đến layout student
        View::composer(['layouts.student', 'layouts.teacher'], function ($view) {
            $student = Auth::guard('student')->user();

            $notifications = Notification::all();

            $view->with('notifications', $notifications);
        });
    }
}

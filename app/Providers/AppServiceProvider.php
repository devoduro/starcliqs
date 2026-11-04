<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        $this->configureRateLimits();
    }

    /**
     * Request limits per visitor IP, adjustable under Site settings → Security & visitors.
     * site:  every public page request
     * forms: contact form, Academy registration and newsletter sign-up
     * login: admin sign-in page (wrong passwords are limited separately in LoginController)
     */
    private function configureRateLimits(): void
    {
        $setting = fn (string $key, int $default) => max(1, (int) Setting::get($key, (string) $default));

        RateLimiter::for('site', fn (Request $request) => Limit::perMinute($setting('rate_limit_per_minute', 120))->by($request->ip()));

        RateLimiter::for('forms', fn (Request $request) => [
            Limit::perMinute(3)->by('forms-minute|' . $request->ip()),
            Limit::perHour($setting('form_limit_per_hour', 10))->by('forms-hour|' . $request->ip()),
        ]);

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(20)->by($request->ip()));
    }
}

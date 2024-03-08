<?php

namespace JohnsTools\EventLogger;

use Illuminate\Support\ServiceProvider;

class EventLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('event-logger', function($app){
            return new EventLogger;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}

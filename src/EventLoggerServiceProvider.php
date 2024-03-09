<?php

namespace JohnsTools\EventLogger;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EventLoggerServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('event-logger', function($app){
            return new EventLogger;
        });
    }

}

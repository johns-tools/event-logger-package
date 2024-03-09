<?php

// Framework
use Illuminate\Support\Facades\Route;

// Controllers
use JohnsTools\EventLogger\Controllers\EventLogAdminController;

Route::get('/event-logger/view-logs', [EventLogAdminController::class, 'viewLogs']);

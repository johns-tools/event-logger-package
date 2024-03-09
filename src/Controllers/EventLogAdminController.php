<?php

namespace JohnsTools\EventLogger\Controllers;

use Illuminate\Routing\Controller;

class EventLogAdminController extends Controller
{
    public function viewLogs()
    {
        dump("Hello from the event log admin!");
    }
}

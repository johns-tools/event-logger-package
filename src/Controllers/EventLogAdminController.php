<?php

namespace JohnsTools\EventLogger\Controllers;

// Packages
use JohnsTools\EventLogger\EventLogger;

// Framework
use Illuminate\Routing\Controller;

class EventLogAdminController extends Controller
{
    protected $eventLogger;
    protected $identifier;
    protected $storageDriver;
    protected $fileMeta;
    protected $filePath;

    public function __construct()
    {
        // Set the parameters for the vent log.
        $this->identity                   = uniqid();
        $this->storageDrive               = 'system_event_logs';
        $this->fileMeta['file_name']      = "log_event_";
        $this->fileMeta['file_extension'] = ".json";
        $this->filePath = $this->fileMeta['file_name'] . $this->identity . $this->fileMeta['file_extension'];

        // Create new instance of the event log.
        $this->eventLogger = new EventLogger($this->identity, $this->storageDriver, $this->fileMeta);
    }

    public function viewLogs()
    {
        $message = "Loading base page for stored event logs.";
        $level   = 0;

        $this->eventLogger->addEvent(__CLASS__, __FUNCTION__, $message, $level);

        dump("Hello from the event log admin!");
    }
}

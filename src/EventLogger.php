<?php

namespace JohnsTools\EventLogger;

// Traits
use JohnsTools\EventLogger\Traits\Utilities as EventLoggerUtilities;

// Support Classes
use JohnsTools\EventLogger\Services\SystemChecks;

class EventLogger
{

    use EventLoggerUtilities;

    protected $log_data;
    protected $events;

    protected $identifier;
    protected $storageDriver = 'system_event_logs';
    protected $fileName = null;
    protected $fileExtension = null;

    public function __construct(String $identifier, $storageDriver, Array $fileMeta)
    {
        // Checks (static)
        SystemChecks::checkFileMeta($fileMeta);
        SystemChecks::checkIdentifier($identifier);

        // After checking, we can now set the required variables local to this class instance.
        $this->identifier = $identifier;
        $this->storageDriver = $storageDriver ?? $this->storageDriver;
        $this->fileName = $fileMeta['file_name'];
        $this->fileExtension = $fileMeta['file_extension'];
    }

    public function addEvent($class, $function, $message, $level):void
    {
        $this->constructEvent(array_merge(
            $this->constructMetaData($class, $function),
            $this->constructMessage($message, $level)
        ));
    }
}

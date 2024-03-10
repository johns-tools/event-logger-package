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

    /**
     * Adds an event with the provided class, function, message, and level.
     *
     * @param string $class The class related to the event.
     * @param string $function The function related to the event.
     * @param string $message The message associated with the event.
     * @param int $level The severity level of the event.
     *
     * @return void
     */
    public function addEvent(sting $class, string $function, string $message, int $level):void
    {
        $this->constructEvent(array_merge(
            $this->constructMetaData($class, $function),
            $this->constructMessage($message, $level)
        ));
    }
}

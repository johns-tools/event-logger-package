<?php

namespace Tests\Feature;

// Service
use JohnsTools\EventLogger\EventLogger;

// Framework
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class EventLoggerServiceTest extends TestCase
{
    protected $eventLogger;
    protected $identifier;
    protected $storageDriver;
    protected $fileMeta;
    protected $filePath;

    public function setUp(): void
    {
        parent::setUp();

        $this->identity = uniqid();
        $this->storageDrive = 'system_event_logs';
        $this->fileMeta['file_name'] = "log_event_";
        $this->fileMeta['file_extension'] = ".json";
        $this->filePath = $this->fileMeta['file_name'] . $this->identity . $this->fileMeta['file_extension'];
        $this->eventLogger = new EventLogger($this->identity, $this->storageDriver, $this->fileMeta);
    }

    public function test_class_instance_creation(): void
    {
        $this->assertInstanceOf(EventLogger::class, $this->eventLogger);
    }

    // *Is dependent on the above class instance test, to run first.
    public function test_add_event(): void
    {
        $message = "Creating a test event log.";
        $level   = 0;

        $this->eventLogger->addEvent(__CLASS__, __FUNCTION__, $message, $level);
        $this->assertTrue(Storage::disk($this->storageDrive)->exists($this->filePath));
    }
}

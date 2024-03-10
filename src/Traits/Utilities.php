<?php

namespace JohnsTools\EventLogger\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

trait Utilities
{

    /**
     * Constructs an event and adds it to the event list and log.
     *
     * This method constructs an event using the provided event data array.
     * It adds the constructed event to the event list and also adds it to the log.
     *
     * @param array $event An array containing the data for the event to be constructed.
     *              The structure of the array should follow the format expected by the log.
     *
     * @return void
     *
    */
    public function constructEvent(Array $event)
    {
        $this->events[] = $event;
        $this->addEventToLog($event);
    }

    /**
     * Adds an event to the log.
     *
     * This method adds an event to the log with the provided event data. It generates an error reference,
     * creates or retrieves the log file, updates the log data with the provided event information, and writes
     * the modified log data back to the storage.
     *
     * @param array $event An array containing the event data, including metadata, message, and optional exception details.
     * @return bool Returns true if the event is successfully added to the log, false otherwise.
     *
     * The array should have the following structure:
     * [
        * 'meta' => [
        *   'class' => 'Name of the class related to the event',
        *   'function' => 'Name of the function related to the event'
        * ],
        *   'message' => [
        *   'message' => 'Message associated with the event',
        *   'level' => 'Severity level of the event'
        * ],
        *   'exception' => [
        *   'message' => 'Message describing the exception (if applicable)'
        * ]
     * ]
     *
    */
    public function addEventToLog(Array $event) : bool
    {
        $logRef         = $this->generateErrorRef();
        $logFileName    = $this->createOrRetrieveLogFile();

        $this->log_data = Storage::disk($this->storageDriver)->get($logFileName);
        $this->log_data = json_decode($this->log_data, true);

        isset($event['meta'])      && $this->writeMetaData($logRef, $event['meta']['class'], $event['meta']['function']);
        isset($event['message'])   && $this->writeMessage($logRef, $event['message']['message'], $event['message']['level']);
        isset($event['exception']) && $this->writeException($logRef, $event['exception']['message']);

        $this->log_data = json_encode($this->log_data);

        return Storage::disk($this->storageDriver)->put($logFileName, $this->log_data);
    }

    /**
     * Creates or retrieves the log file.
     *
     * This method creates or retrieves the log file using the specified file name, identifier, and file extension.
     * It utilises the configured storage driver to store the log file.
     *
     * @return string The name of the created or retrieved log file, including the file extension.
     *
     */
    private function createOrRetrieveLogFile() : string
    {
        $fileNameWithExtension = ($this->fileName . $this->identifier . $this->fileExtension);
        Storage::disk($this->storageDriver)->put($fileNameWithExtension, "");
        return $fileNameWithExtension;
    }

    /**
     * Generates a unique error reference.
     *
     * This method generates a unique error reference using the `uniqid()` function.
     * The generated error reference can be used to uniquely identify an error or event.
     *
     * @return string A unique error reference generated using the `uniqid()` function.
     *
     */
    private function generateErrorRef(){
        return uniqid();
    }

    /**
     * Constructs metadata for an event.
     *
     * This method constructs metadata for an event based on the provided class and function names.
     * The metadata includes information about the class and function associated with the event.
     *
     * @param string $class The name of the class related to the event.
     * @param string $function The name of the function related to the event.
     *
     * @return array An array containing metadata for the event, with keys 'meta', 'class', and 'function'.
     *
     */
    public function constructMetaData($class, $function) : array
    {
        return ['meta' => compact('class', 'function')];
    }

    /**
     * Constructs a message for an event.
     *
     * This method constructs a message for an event along with its severity level.
     * The message contains information about the event and its severity.
     *
     * @param string $message The message associated with the event.
     * @param int $level The severity level of the event.
     *
     * @return array An array containing the constructed message for the event, with keys 'message' and 'level'.
     *
     */
    public function constructMessage(string $message, int $level) : array
    {
        return ['message' => compact('message', 'level')];
    }

    /**
     * Constructs an exception message for an event.
     *
     * This method constructs an exception message for an event using the provided message.
     * The exception message provides additional information about an exceptional condition that occurred during the event.
     *
     * @param string $message The message describing the exception.
     * @return array An array containing the constructed exception message for the event, with the key 'exception'.
     *
     */
    public function constructException(string $message) : array
    {
        return ['exception' => compact('message')];
    }

    /**
     * Writes a message to the log data.
     *
     * This method writes a message to the log data array under the 'messages' key.
     * The message is associated with the provided log reference and access level.
     *
     * @param string $logRef The reference associated with the log entry.
     * @param string $message The message to be written to the log.
     * @param int    $level The access level or severity level of the message.
     *
     * @return void
     */
    private function writeMessage(string $logRef, string $message, int $level): void
    {
        $this->log_data['messages'][] = [
            "log_ref"      => $logRef,
            "message"      => $message,
            "access_level" => $level
        ];
    }

    /**
     * Writes an exception to the log data.
     *
     * This method writes an exception message to the log data array under the 'exceptions' key.
     * The exception message is associated with the provided log reference.
     *
     * @param string $logRef The reference associated with the log entry.
     * @param string $message The message describing the exception.
     *
     * @return void
     */
    private function writeException(string $logRef, int $message): void
    {
        $this->log_data['exceptions'][] = [
            "log_ref" => $logRef,
            "exception_message" => $message
        ];
    }

    /**
     * Writes metadata to the log data.
     *
     * This method writes metadata to the log data array under the 'meta_data' key.
     * The metadata includes information such as class, function, and date/time associated with the log entry.
     *
     * @param string $logRef The reference associated with the log entry.
     * @param string $class The full class name associated with the log entry.
     * @param string $function The function name associated with the log entry.
     *
     * @return void
     */
    private function writeMetaData(string $logRef, string $class, string $function): void
    {
        $this->log_data['meta_data'][] = [
            "log_ref"    => $logRef,
            "class_full" => $class,
            "class"      => (new \ReflectionClass($class))->getShortName(),
            "function"   => $function,
            "date_time"  => Carbon::now()->toDateTimeString()
        ];
    }

}

<?php

namespace JohnsTools\EventLogger\Class;

// Framework
use Exception;

class Checks
{

    public static function checkFileMeta(Array $fileMeta):void
    {
        // Check we have the required meta data, that will be used to create the file name.
        if(!isset($fileMeta['file_name']) or !isset($fileMeta['file_extension']))
        {
            throw new Exception("Missing required data from fileMeta.");
        }
    }

    public static function checkIdentifier(String $identifier):void
    {
        // Check an identifier is passed in, will be used when unique identity is required.
        if(!$identifier)
        {
            throw new Exception("Missing required parameter `identifier`.");
        }
    }

}

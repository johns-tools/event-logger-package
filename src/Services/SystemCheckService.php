<?php

namespace JohnsTools\EventLogger\Services;

// Framework
use Exception;

class SystemCheckService
{

    /**
     * Checks if the necessary metadata is present in the file metadata array.
     *
     * This function ensures that both 'file_name' and 'file_extension' keys
     * are set in the provided file metadata array. If either is missing,
     * an Exception is thrown.
     *
     * @param array $fileMeta An array containing the file metadata.
     * @throws Exception If either 'file_name' or 'file_extension' is not set in $fileMeta.
     */
    public static function checkFileMeta(array $fileMeta) : void
    {
        if(!isset($fileMeta['file_name']) or !isset($fileMeta['file_extension']))
        {
            throw new Exception("Missing required data from fileMeta.");
        }
    }

    /**
     * Checks if the identifier parameter is provided.
     *
     * This function validates that the provided identifier is not empty.
     * If it is, an Exception is thrown indicating that the identifier
     * parameter is required but missing.
     *
     * @param string $identifier The identifier to be validated.
     * @throws Exception If the identifier is empty.
     */
    public static function checkIdentifier(string $identifier) : void
    {
        if(!$identifier)
        {
            throw new Exception("Missing required parameter `identifier`.");
        }
    }

}

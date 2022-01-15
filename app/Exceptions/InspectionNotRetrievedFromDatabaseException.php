<?php

namespace App\Exceptions;

use Exception;

class InspectionNotRetrievedFromDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't retrieve inspection.", 404);
    }
}

<?php

namespace App\Exceptions;

use Exception;

class InsuranceNotRetrievedFromDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't retrieve insurance.", 404);
    }
}

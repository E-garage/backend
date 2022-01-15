<?php

namespace App\Exceptions;

use Exception;

class RefuelingNotDeletedFromDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("Refueling couldn't be deleted.", 422);
    }
}

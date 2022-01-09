<?php

namespace App\Exceptions;

use Exception;

class FamilyNotSavedToDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't save family to database.", 500);
    }
}

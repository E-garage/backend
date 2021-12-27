<?php

namespace App\Exceptions;

use Exception;

class UserNotSavedToDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't save the user.", 500);
    }
}

<?php

namespace App\Exceptions;

use Exception;

class UserNotDeletedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't find or delete the user.", 500);
    }
}

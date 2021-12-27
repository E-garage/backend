<?php

namespace App\Exceptions;

use Exception;

class UserNotUpdatedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't find or update the user.", 500);
    }
}

<?php

namespace App\Exceptions;

use Exception;

class UserNotRegisteredException extends Exception
{
    public function __construct()
    {
        parent::__construct('Couldn\'t save user to database.', 500);
    }
}

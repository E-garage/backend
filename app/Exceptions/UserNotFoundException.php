<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn\'t find the user.", 500);
    }
}

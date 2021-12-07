<?php

namespace App\Exceptions;

use Exception;

class UserCredentialsInvalidExecption extends Exception
{
    public function __construct()
    {
        parent::__construct('User Credentials is Invalid,', 401);
    }
}

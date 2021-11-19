<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class UserCredentialsInvalidExecption extends Exception
{
    public function __construct()
    {
        parent::__construct("User Credentials is Invalid,",500);
    }
}

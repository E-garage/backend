<?php

namespace App\Exceptions;

use Exception;

class AuthorizedUserNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't get authorized user", 404);
    }
}

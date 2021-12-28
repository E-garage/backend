<?php

namespace App\Exceptions;

use Exception;

class TokenNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Token Not Found,', 404);
    }
}

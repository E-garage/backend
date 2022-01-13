<?php

namespace App\Exceptions;

use Exception;

class CarNotDetachedFromFamilyException extends Exception
{
    public function __construct()
    {
        parent::__construct("couldn't detach car from family.", 500);
    }
}

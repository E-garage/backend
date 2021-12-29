<?php

namespace App\Exceptions;

use Exception;

class CarNotSaveToDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't save car.", 500);
    }
}

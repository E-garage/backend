<?php

namespace App\Exceptions;

use Exception;

class CarNotUpdatedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't update car.", 422);
    }
}

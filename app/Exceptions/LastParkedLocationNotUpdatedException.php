<?php

namespace App\Exceptions;

use Exception;

class LastParkedLocationNotUpdatedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't update the location.", 500);
    }
}

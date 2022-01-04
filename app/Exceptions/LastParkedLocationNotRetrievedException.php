<?php

namespace App\Exceptions;

use Exception;

class LastParkedLocationNotRetrievedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't retrieve location.", 500);
    }
}

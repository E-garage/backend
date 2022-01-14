<?php

namespace App\Exceptions;

use Exception;

class CarNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't find car.", 500);
    }
}

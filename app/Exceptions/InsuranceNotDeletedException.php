<?php

namespace App\Exceptions;

use Exception;

class InsuranceNotDeletedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't delete insurance", 500);
    }
}

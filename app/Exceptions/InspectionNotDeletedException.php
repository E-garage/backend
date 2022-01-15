<?php

namespace App\Exceptions;

use Exception;

class InspectionNotDeletedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't delete inspection.", 500);
    }
}

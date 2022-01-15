<?php

namespace App\Exceptions;

use Exception;

class InspectionNotUpdatedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't update inspection.", 422);
    }
}

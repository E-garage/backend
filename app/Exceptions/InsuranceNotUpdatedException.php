<?php

namespace App\Exceptions;

use Exception;

class InsuranceNotUpdatedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't update insurance.", 422);
    }
}

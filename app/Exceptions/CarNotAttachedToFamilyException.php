<?php

namespace App\Exceptions;

use Exception;

class CarNotAttachedToFamilyException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't attach car to family.", 422);
    }
}

<?php

namespace App\Exceptions;

use Exception;

class FamilyNotUpdatedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't update the family.", 422);
    }
}

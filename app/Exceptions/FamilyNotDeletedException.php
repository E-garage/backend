<?php

namespace App\Exceptions;

use Exception;

class FamilyNotDeletedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't delete family.", 500);
    }
}

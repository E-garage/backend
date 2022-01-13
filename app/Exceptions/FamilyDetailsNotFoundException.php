<?php

namespace App\Exceptions;

use Exception;

class FamilyDetailsNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't get the family's details.", 500);
    }
}

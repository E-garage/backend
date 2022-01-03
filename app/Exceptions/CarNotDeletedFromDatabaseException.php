<?php

namespace App\Exceptions;

use Exception;

class CarNotDeletedFromDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("Car couldn't be deleted.", 500);
    }
}

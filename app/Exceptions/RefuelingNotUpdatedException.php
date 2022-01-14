<?php


namespace App\Exceptions;

use Exception;

class RefuelingNotUpdatedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't Update refueling.", 500);
    }
}

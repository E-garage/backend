<?php


namespace App\Exceptions;

use Exception;


class RefuelingNotSavedToDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't save refueling.", 500);
    }
}

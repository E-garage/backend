<?php

namespace App\Exceptions;

use Exception;

class MappingDataToObjectException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't map data to object.", 500);
    }
}

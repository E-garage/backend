<?php

namespace App\Exceptions;

use Exception;

class CarBudgetNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't finde estimated budget for the car.", 500);
    }
}

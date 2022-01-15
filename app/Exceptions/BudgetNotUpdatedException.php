<?php

namespace App\Exceptions;

use Exception;

class BudgetNotUpdatedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't update car's budget.", 422);
    }
}

<?php

namespace App\Exceptions;

use Exception;

class BudgetNotDeletedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't delete car's budget.", 500);
    }
}

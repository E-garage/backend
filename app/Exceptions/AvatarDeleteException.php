<?php

namespace App\Exceptions;

use Exception;

class AvatarDeleteException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't delete avatar.", 500);
    }
}

<?php

namespace App\Exceptions;

use Exception;

class RefuelingReceiptNotRemovedFromStorageException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't remove thumbnail.", 422);
    }
}

<?php

namespace App\Exceptions;

use Exception;

class CarsThumbnailNotRemovedFromStorage extends Exception
{
    public function __construct() {
        parent::__construct("Couldn't remove thumbnail.", 500);
    }
}

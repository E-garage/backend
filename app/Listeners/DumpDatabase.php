<?php

namespace App\Listeners;

use Artisan;

class DumpDatabase
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('schema:dump');
    }
}

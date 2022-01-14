<?php

declare(strict_types = 1);

namespace App\Factories;

use App\Models\Refueling;

class RefuelingFactory
{
    private Refueling $refueling;

    public function __construct()
    {
        $this->refueling = new Refueling();
    }

    public function createFromRequest(array $data): Refueling
    {
        $this->refueling->owner_id = $data['owner_id'];
        $this->refueling->car_id = $data['car_id'];
        $this->refueling->date = $data['date'];
        $this->refueling->FuelType = $data['FuelType'];
        $this->refueling->TotalPrice = $data['TotalPrice'];
        $this->refueling->amount = $data['amount'];


        return $this->refueling;
    }
}

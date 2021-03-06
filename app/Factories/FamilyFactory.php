<?php

declare(strict_types = 1);

namespace App\Factories;

use App\Models\Family;
use Auth;

class FamilyFactory
{
    private Family $family;

    public function __construct()
    {
        $this->family = new Family();
    }

    public function createFormRequest(array $data): Family
    {
        $this->family->owner_id = Auth::user()->id; //@phpstan-ignore-line
        $this->family->name = $data['name'];
        $this->family->description = $data['description'] ?? null;

        return $this->family;
    }
}

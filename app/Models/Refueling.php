<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refueling extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'FuelType',
        'amount',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}

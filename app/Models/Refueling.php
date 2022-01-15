<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refueling extends Model
{
    use HasFactory;

    protected $table = 'refuelings';

    protected $fillable = [
        'car_id',
        'date',
        'FuelType',
        'amount',
        'TotalPrice',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}

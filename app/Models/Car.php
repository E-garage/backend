<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    public const AVAILABLE = 'available';
    public const INACTIVE = 'inactive';

    protected $fillable = [
        'brand',
        'description',
        'details',
    ];

    protected $casts = [
        'details' => 'json',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }

    public function refueling(): HasMany
    {
        return $this->hasMany(Refueling::class, 'car_id');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function changeStatus(): void
    {
        $status = $this->getAttribute('availability');
        if ($status == 'available') {
            $this->setAttribute('availability', self::INACTIVE);
        } else {
            $this->setAttribute('availability', self::AVAILABLE);
        }
    }
}

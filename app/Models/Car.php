<?php

namespace App\Models;

use App\Events\CarCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected $dispatchesEvents = [
        'created' => CarCreated::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function insurance(): HasOne
    {
        return $this->hasOne(Insurance::class);
    }

    public function inspection(): HasOne
    {
        return $this->hasOne(Inspection::class);
    }

    public function budget(): HasOne
    {
        return $this->hasOne(EstimatedBudget::class);
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

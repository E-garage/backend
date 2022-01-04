<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LastParkedLocation extends Model
{
    protected $table = 'last_parked_locations';

    protected $fillable = [
        'user_id',
        'longitude',
        'latitude',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
}

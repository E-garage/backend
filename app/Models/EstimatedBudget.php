<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstimatedBudget extends Model
{
    use HasFactory;

    protected $table = 'estimated_budget';

    protected $fillable = [
        'original_budget',
        'budget_left',
        'last_payment_amount',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}

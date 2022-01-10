<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    use HasFactory;

    protected $table = 'families';

    protected $fillable = [
        'name',
        'description',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'family_user', 'family_id', 'user_id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}

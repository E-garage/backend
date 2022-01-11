<?php

namespace App\Models;

use App\Repositories\CarRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    public const AVAILABLE = 'available';
    public const INACTIVE = 'inactive';

    protected $fillable = [
        'owner_id',
        'brand',
        'description',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
    public function changeStatus()
    {
        $status = $this->getAttribute('availability');
        if ($status=="available"){
            $this->setAttribute('availability',Car::INACTIVE)->saveOrFail();
        }
        else{
            $this->setAttribute('availability',Car::AVAILABLE)->saveOrFail();
        }
    }
}

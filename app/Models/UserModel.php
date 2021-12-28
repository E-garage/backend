<?php

declare(strict_types = 1);

namespace App\Models;

use App\Traits\Uuids;
use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * App\Models\UserModel.
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $id
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property int|null $notifications_count
 * @property Collection|PersonalAccessToken[] $tokens
 * @property int|null $tokens_count
 *
 * @method static Builder|UserModel newModelQuery()
 * @method static Builder|UserModel newQuery()
 * @method static Builder|UserModel query()
 * @method static Builder|UserModel whereCreatedAt($value)
 * @method static Builder|UserModel whereEmail($value)
 * @method static Builder|UserModel whereEmailVerifiedAt($value)
 * @method static Builder|UserModel whereId($value)
 * @method static Builder|UserModel whereName($value)
 * @method static Builder|UserModel wherePassword($value)
 * @method static Builder|UserModel whereRememberToken($value)
 * @method static Builder|UserModel whereUpdatedAt($value)
 * @mixin Eloquent
 */
class UserModel extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Uuids;

    public const ADMIN = 'admin';
    public const USER = 'user';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

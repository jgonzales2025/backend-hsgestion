<?php

namespace App\Modules\User\Infrastructure\Model;

use App\Modules\UserAssignment\Infrastructure\Models\EloquentUserAssignment;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class EloquentUser extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $table = 'users';

    protected $guard_name = 'api';

    protected $fillable = ['username', 'firstname', 'lastname', 'password', 'status'];

    protected $hidden = ['password', 'remember_token'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(EloquentUserAssignment::class, 'user_id');
    }
}

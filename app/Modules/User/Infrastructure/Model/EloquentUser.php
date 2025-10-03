<?php

namespace App\Modules\User\Infrastructure\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = ['status_name'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}

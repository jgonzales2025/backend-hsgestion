<?php

namespace App\Modules\User\Infrastructure\Model;

use App\Models\Role;
use App\Modules\Bank\Infrastructure\Models\EloquentBank;
use App\Modules\Menu\Infrastructure\Models\EloquentMenu;
use App\Modules\User\Domain\Entities\User;
use App\Modules\UserAssignment\Infrastructure\Models\EloquentUserAssignment;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class EloquentUser extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $table = 'users';

    protected $guard_name = 'api';

    protected $fillable = ['username', 'firstname', 'lastname', 'password', 'status', 'st_login', 'failed_attempts'];

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

    public function roles(): BelongsToMany
    {
        return $this->morphToMany(
            Role::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        );
    }

    public function banks(): HasMany
    {
        return $this->hasMany(EloquentBank::class, 'user_id');
    }

    public function toDomain(EloquentUser $eloquentUser): User
    {
        return new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: null,
            status: $eloquentUser->status,
            roles: null,
            assignment: null
        );
    }

}

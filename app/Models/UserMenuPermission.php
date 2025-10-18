<?php

namespace App\Models;

use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;

class UserMenuPermission extends Model
{
    protected $fillable = ['user_id', 'role_id', 'menu_id'];

    public function user()
    {
        return $this->belongsTo(EloquentUser::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }
}

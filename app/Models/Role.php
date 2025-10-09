<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends \Spatie\Permission\Models\Role
{
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_role');
    }
}

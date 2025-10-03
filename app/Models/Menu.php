<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    protected $fillable = [
        'label',
        'parent_id',
        'order',
        'status',
        'type'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_role');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: Menús ordenados
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

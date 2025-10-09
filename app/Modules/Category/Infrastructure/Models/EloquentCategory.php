<?php

namespace App\Modules\Category\Infrastructure\Models;

use App\Modules\SubCategory\Infrastructure\Models\EloquentSubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentCategory extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'status'];
    protected $hidden = ['created_at', 'updated_at'];

    public function subCategories(): HasMany
    {
        return $this->hasMany(EloquentSubCategory::class, 'category_id');
    }
}

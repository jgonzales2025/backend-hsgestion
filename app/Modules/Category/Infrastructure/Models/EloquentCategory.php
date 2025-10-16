<?php

namespace App\Modules\Category\Infrastructure\Models;

use App\Modules\Category\Domain\Entities\Category;
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
      public function toDomain(EloquentCategory $eloquentCategory): Category
    {
        return new Category(
            id: $eloquentCategory->id,
            name:$eloquentCategory->name,
            status:$eloquentCategory->status
            
        );
    }
}

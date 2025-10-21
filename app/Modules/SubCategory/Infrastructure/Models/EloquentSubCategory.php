<?php

namespace App\Modules\SubCategory\Infrastructure\Models;

use App\Modules\Category\Domain\Entities\Category;
use App\Modules\Category\Infrastructure\Models\EloquentCategory;
use App\Modules\SubCategory\Domain\Entities\SubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentSubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = ['name', 'category_id', 'status'];
    protected $hidden = ['created_at', 'updated_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EloquentCategory::class, 'category_id');
    }
    public function toDomain(EloquentSubCategory $eloquentCategory): SubCategory
    {
        return new SubCategory(
            id: $eloquentCategory->id,
            name: $eloquentCategory->name,
            category_id: $eloquentCategory->category_id,
            category_name: $eloquentCategory->category_name,
            status: $eloquentCategory->status
        );
    }
}

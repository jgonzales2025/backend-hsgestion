<?php

namespace App\Modules\SubCategory\Infrastructure\Models;

use App\Modules\Category\Infrastructure\Models\EloquentCategory;
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
}

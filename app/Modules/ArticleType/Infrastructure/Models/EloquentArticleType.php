<?php

namespace App\Modules\ArticleType\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentArticleType extends Model
{
    protected $table = 'article_type';

    protected $fillable = [
        'name',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
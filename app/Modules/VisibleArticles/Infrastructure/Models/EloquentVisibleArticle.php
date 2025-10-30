<?php

namespace App\Modules\VisibleArticles\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentVisibleArticle extends Model
{
    protected $table = 'visible_articles';

    protected $fillable = ['company_id', 'branch_id', 'article_id', 'user_id', 'status'];

    protected $hidden = ['created_at', 'updated_at'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }
    public function article(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id');
    }
    
}

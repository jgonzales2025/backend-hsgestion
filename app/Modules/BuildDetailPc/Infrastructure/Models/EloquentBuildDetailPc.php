<?php

namespace App\Modules\BuildDetailPc\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\BuildPc\Infrastructure\Models\EloquentBuildPc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentBuildDetailPc extends Model
{
    protected $table = 'build_detail_pc_tabla';
    protected $fillable = [
        'id',
        'build_pc_id',
        'article_id',
        'quantity',
        'price',
        'subtotal',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function buildPc(): BelongsTo
    {
        return $this->belongsTo(EloquentBuildPc::class, 'build_pc_id', 'id');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id', 'id');
    }
}

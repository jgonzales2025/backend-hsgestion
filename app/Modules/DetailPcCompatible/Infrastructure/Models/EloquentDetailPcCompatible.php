<?php

namespace App\Modules\DetailPcCompatible\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentDetailPcCompatible extends Model
{
    protected $table = 'detail_pc_compatible_tabla';

    protected $fillable = [
        'article_major_id',
        'article_accesory_id',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function articleMajor(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_major_id', 'id');
    }

    public function articleAccessory(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_accesory_id', 'id');
    }
}

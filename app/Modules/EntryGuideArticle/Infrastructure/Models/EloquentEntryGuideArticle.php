<?php

namespace App\Modules\EntryGuideArticle\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentEntryGuideArticle extends Model
{
    protected $table = 'entry_guide_article';

    protected $fillable = [
        'entry_guide_id',
        'article_id',
        'description',
        'quantity',
        'saldo',
        'subtotal',
        'total',
        'total_descuento',
        'descuento',
    ];  

    protected $hidden = ['created_at', 'updated_at'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id', 'id');
    }
}

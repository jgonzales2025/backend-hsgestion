<?php

namespace App\Modules\DispatchArticle\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentDispatchArticle extends Model
{

    protected $table = "dispatch_article";

    protected $fillable = [
        'dispatch_id',
        'article_id',
        'quantity',
        'weight',
        'saldo',
        'name',
        'subtotal_weight',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function dispatchArticle(): BelongsTo
    {
        return $this->belongsTo(EloquentDispatchNote::class, 'dispatch_id');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id');
    }
}
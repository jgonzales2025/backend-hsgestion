<?php

namespace App\Modules\ReferenceCode\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentReferenceCode extends Model{
    protected $table =  'reference_codes';
        protected $fillable = [
        'ref_code',
        'article_id',
        'date_at',
        'status'
    ];
    protected $hidden = ['created_at', 'update_at'];

     public function article(): BelongsTo
{
    return $this->belongsTo(EloquentArticle::class, 'article_id');
}

}
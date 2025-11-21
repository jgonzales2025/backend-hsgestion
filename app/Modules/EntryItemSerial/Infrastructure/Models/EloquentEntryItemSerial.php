<?php

namespace App\Modules\EntryItemSerial\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\EntryGuides\Infrastructure\Models\EloquentEntryGuide;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentEntryItemSerial extends Model{
    protected $table = 'entry_item_serials';

    protected $fillable = [
        'id',
        'entry_guide_id',
        'article_id',
        'serial',
        'branch_id'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function entryGuide(): BelongsTo
    {
        return $this->belongsTo(EloquentEntryGuide::class, 'entry_guide_id');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id');
    }
}

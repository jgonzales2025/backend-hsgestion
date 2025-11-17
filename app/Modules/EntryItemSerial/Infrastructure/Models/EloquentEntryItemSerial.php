<?php

namespace App\Modules\EntryItemSerial\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

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
}

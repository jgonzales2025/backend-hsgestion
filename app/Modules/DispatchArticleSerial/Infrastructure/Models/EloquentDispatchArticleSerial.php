<?php

namespace App\Modules\DispatchArticleSerial\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentDispatchArticleSerial extends Model
{
    protected $table = 'dispatch_article_serials';
    protected $fillable = [
        'dispatch_note_id',
        'article_id',
        'serial',
        'status',
        'origin_branch_id',
        'destination_branch_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}

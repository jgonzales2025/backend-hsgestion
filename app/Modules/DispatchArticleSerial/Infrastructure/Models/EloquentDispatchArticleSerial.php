<?php

namespace App\Modules\DispatchArticleSerial\Infrastructure\Models;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use Illuminate\Database\Eloquent\Model;

class EloquentDispatchArticleSerial extends Model
{
    protected $table = 'dispatch_article_serials';
    protected $fillable = [
        'dispatch_note_id',
        'article_id',
        'serial',
        'emission_reasons_id',
        'status',
        'origin_branch_id',
        'destination_branch_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function article()
    {
        return $this->belongsTo(EloquentArticle::class, 'article_id');
    }

    public function originBranch()
    {
        return $this->belongsTo(EloquentBranch::class, 'origin_branch_id');
    }

    public function destinationBranch()
    {
        return $this->belongsTo(EloquentBranch::class, 'destination_branch_id');
    }
}

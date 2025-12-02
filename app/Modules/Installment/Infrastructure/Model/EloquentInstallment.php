<?php

namespace App\Modules\Installment\Infrastructure\Model;

use App\Modules\Sale\Domain\Entities\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentInstallment extends Model
{
    protected $table = 'installments';

    protected $fillable = [
        'installment_number',
        'sale_id',
        'amount',
        'due_date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}

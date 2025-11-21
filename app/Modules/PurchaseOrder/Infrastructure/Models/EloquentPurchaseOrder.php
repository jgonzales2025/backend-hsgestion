<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentPurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    protected $fillable = [
        'company_id',
        'branch_id',
        'serie',
        'correlative',
        'date',
        'delivery_date',
        'contact',
        'order_number_supplier',
        'observations',
        'supplier_id',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'supplier_id');
    }
}

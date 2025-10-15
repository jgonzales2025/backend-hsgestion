<?php

namespace App\Modules\Bank\Infrastructure\Models;

use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentBank extends Model
{
    protected $table = 'banks';

    protected $fillable = ['name', 'account_number', 'currency_type_id', 'user_id', 'created_at', 'company_id', 'status'];

    protected $hidden = ['updated_at'];

    public function currencyType(): BelongsTo
    {
        return $this->belongsTo(EloquentCurrencyType::class, 'currency_type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }
}

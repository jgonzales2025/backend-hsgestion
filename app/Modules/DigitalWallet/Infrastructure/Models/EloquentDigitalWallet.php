<?php

namespace App\Modules\DigitalWallet\Infrastructure\Models;

use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentDigitalWallet extends Model
{
    protected $table = 'digital_wallets';

    protected $fillable = ['name', 'phone', 'company_id', 'user_id', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }
}

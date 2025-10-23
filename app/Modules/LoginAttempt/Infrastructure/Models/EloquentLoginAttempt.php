<?php

namespace App\Modules\LoginAttempt\Infrastructure\Models;

use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentLoginAttempt extends Model
{
    protected $table = 'login_attempts';

    protected $fillable = [
        'username',
        'user_id',
        'successful',
        'ip_address',
        'user_agent',
        'failure_reason',
        'failed_attempts_count',
        'company_id',
        'role_id'
    ];

    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }
}

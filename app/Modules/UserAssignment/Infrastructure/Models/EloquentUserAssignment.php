<?php

namespace App\Modules\UserAssignment\Infrastructure\Models;

use App\Models\Company;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentUserAssignment extends Model
{
    protected $table = 'user_assignments';

    protected $fillable = ['user_id', 'company_id', 'branch_id', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}

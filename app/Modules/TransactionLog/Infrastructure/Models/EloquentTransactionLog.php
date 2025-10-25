<?php

namespace App\Modules\TransactionLog\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentTransactionLog extends Model
{
    protected $table = 'transaction_logs';

    protected $fillable = [
        'user_id',
        'role_id',
        'role_name',
        'description_log',
        'action',
        'company_id',
        'branch_id',
        'document_type_id',
        'serie',
        'correlative',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $hidden = ['updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'document_type_id');
    }
}

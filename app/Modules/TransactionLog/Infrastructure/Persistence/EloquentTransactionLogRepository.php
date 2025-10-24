<?php

namespace App\Modules\TransactionLog\Infrastructure\Persistence;

use App\Models\Role;
use App\Modules\TransactionLog\Domain\Entities\TransactionLog;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\TransactionLog\Infrastructure\Models\EloquentTransactionLog;

class EloquentTransactionLogRepository implements TransactionLogRepositoryInterface
{

    public function save(TransactionLog $transactionLog): void
    {
        $roleId = Role::findByName($transactionLog->getRoleName())->id;

        EloquentTransactionLog::create([
            'user_id' => $transactionLog->getUser()->getId(),
            'role_id' =>$roleId,
            'role_name' => $transactionLog->getRoleName(),
            'description_log' => $transactionLog->getDescriptionLog(),
            'action' => $transactionLog->getAction(),
            'company_id' => $transactionLog->getCompany()->getId(),
            'branch_id' => $transactionLog->getBranch()->getId(),
            'document_type_id' => $transactionLog->getDocumentType()->getId(),
            'serie' => $transactionLog->getSerie(),
            'correlative' => $transactionLog->getCorrelative(),
            'ip_address' => $transactionLog->getIpAddress(),
            'user_agent' => $transactionLog->getUserAgent(),
        ]);
    }
}

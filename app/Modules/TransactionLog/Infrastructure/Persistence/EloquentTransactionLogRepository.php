<?php

namespace App\Modules\TransactionLog\Infrastructure\Persistence;

use App\Models\Role;
use App\Modules\TransactionLog\Domain\Entities\TransactionLog;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\TransactionLog\Infrastructure\Models\EloquentTransactionLog;

class EloquentTransactionLogRepository implements TransactionLogRepositoryInterface
{
    public function findAll(): array
    {
        $eloquentTransactionLog = EloquentTransactionLog::all()->sortByDesc('created_at');

        return $eloquentTransactionLog->map(function ($eloquentTransactionLog){
            return new TransactionLog(
                id:  $eloquentTransactionLog->id,
                user: $eloquentTransactionLog->user->toDomain($eloquentTransactionLog->user),
                roleId: $eloquentTransactionLog->role_id,
                role_name: $eloquentTransactionLog->role_name,
                description_log: $eloquentTransactionLog->description_log,
                action: $eloquentTransactionLog->action,
                company: $eloquentTransactionLog->company->toDomain($eloquentTransactionLog->company),
                branch: $eloquentTransactionLog->branch->toDomain($eloquentTransactionLog->branch),
                documentType: $eloquentTransactionLog->documentType->toDomain($eloquentTransactionLog->documentType),
                serie: $eloquentTransactionLog->serie,
                correlative: $eloquentTransactionLog->correlative,
                ipAddress: $eloquentTransactionLog->ip_address,
                userAgent: $eloquentTransactionLog->user_agent,
            );
        })->toArray();
    }

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

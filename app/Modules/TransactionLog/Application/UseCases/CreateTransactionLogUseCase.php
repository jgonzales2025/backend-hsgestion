<?php

namespace App\Modules\TransactionLog\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Domain\Entities\TransactionLog;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class CreateTransactionLogUseCase
{
    public function __construct(
        private readonly TransactionLogRepositoryInterface $transactionLogRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly BranchRepositoryInterface $branchRepository,
    ){}

    public function execute(TransactionLogDTO $transactionLogDTO): void
    {
        $userUseCase = new GetUserByIdUseCase($this->userRepository);
        $user = $userUseCase->execute($transactionLogDTO->user_id);

        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($transactionLogDTO->company_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($transactionLogDTO->branch_id);

        $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentTypeRepository);
        $documentType = $documentTypeUseCase->execute($transactionLogDTO->document_type_id);

        $transactionLog = new TransactionLog(
            id: 0,
            user: $user,
            roleId: null,
            role_name: $transactionLogDTO->role_name,
            description_log: $transactionLogDTO->description_log,
            observations: $transactionLogDTO->observations,
            action: $transactionLogDTO->action,
            company: $company,
            branch: $branch,
            documentType: $documentType,
            serie: $transactionLogDTO->serie,
            correlative: $transactionLogDTO->correlative,
            ipAddress: $transactionLogDTO->ip_address,
            userAgent: $transactionLogDTO->user_agent,
        );

        $this->transactionLogRepository->save($transactionLog);
    }
}

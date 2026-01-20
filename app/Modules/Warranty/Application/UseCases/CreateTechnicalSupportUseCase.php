<?php

namespace App\Modules\Warranty\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Warranty\Application\DTOs\TechnicalSupportDTO;
use App\Modules\Warranty\Domain\Entities\TechnicalSupport;
use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

class CreateTechnicalSupportUseCase
{
    public function __construct(
        private readonly WarrantyRepositoryInterface $warrantyRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly BranchRepositoryInterface $branchRepository
    ){}

    public function execute(TechnicalSupportDTO $technicalSupportDTO): int
    {
        $lastDocumentNumber = $this->warrantyRepository->getLastDocumentNumber($technicalSupportDTO->serie);
        $technicalSupportDTO->correlative = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($technicalSupportDTO->company_id);
        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($technicalSupportDTO->branch_id);

        $technicalSupport = new TechnicalSupport(
            id: 0,
            document_type_warranty_id: $technicalSupportDTO->document_type_warranty_id,
            company: $company,
            branch: $branch,
            serie: $technicalSupportDTO->serie,
            correlative: $technicalSupportDTO->correlative,
            date: $technicalSupportDTO->date,
            customer_phone: $technicalSupportDTO->customer_phone,
            customer_email: $technicalSupportDTO->customer_email,
            failure_description: $technicalSupportDTO->failure_description,
            observations: $technicalSupportDTO->observations,
            diagnosis: $technicalSupportDTO->diagnosis,
            contact: $technicalSupportDTO->contact
        );

        return $this->warrantyRepository->saveTechnicalSupport($technicalSupport);
    }
}
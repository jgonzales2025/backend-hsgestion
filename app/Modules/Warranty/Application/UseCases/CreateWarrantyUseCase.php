<?php

namespace App\Modules\Warranty\Application\UseCases;

use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\EntryGuides\Application\UseCases\FindByIdEntryGuideUseCase;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\Sale\Application\UseCases\FindByIdSaleUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Warranty\Application\DTOs\WarrantyDTO;
use App\Modules\Warranty\Domain\Entities\Warranty;
use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;
use App\Modules\WarrantyStatus\Application\UseCases\FindByIdWarrantyStatusUseCase;
use App\Modules\WarrantyStatus\Domain\Interfaces\WarrantyStatusRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

class CreateWarrantyUseCase
{

    public function __construct(
        private readonly WarrantyRepositoryInterface $warrantyRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly EntryGuideRepositoryInterface $entryGuideRepository,
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly WarrantyStatusRepositoryInterface $warrantyStatusRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService){}
    public function execute(WarrantyDTO $warrantyDTO): int
    {
        $lastDocumentNumber = $this->warrantyRepository->getLastDocumentNumber($warrantyDTO->serie);
        $warrantyDTO->correlative = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($warrantyDTO->company_id);
        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($warrantyDTO->branch_id);
        $branchSaleUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branchSale = $branchSaleUseCase->execute($warrantyDTO->branch_sale_id);
        $articleUseCase = new FindByIdArticleUseCase($this->articleRepository);
        $article = $articleUseCase->execute($warrantyDTO->article_id);
        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($warrantyDTO->customer_id);
        $supplierUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $supplier = $supplierUseCase->execute($warrantyDTO->supplier_id);
        $entryGuideUseCase = new FindByIdEntryGuideUseCase($this->entryGuideRepository);
        $entryGuide = $entryGuideUseCase->execute($warrantyDTO->entry_guide_id);
        $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
        $referenceSale = $saleUseCase->execute($warrantyDTO->reference_sale_id);
        $warrantyStatusUseCase = new FindByIdWarrantyStatusUseCase($this->warrantyStatusRepository);
        $warrantyStatus = $warrantyStatusUseCase->execute($warrantyDTO->warranty_status_id);
        $warranty = new Warranty(
            id: 0,
            document_type_warranty_id: $warrantyDTO->document_type_warranty_id,
            company: $company,
            branch: $branch,
            branch_sale: $branchSale,
            serie: $warrantyDTO->serie,
            correlative: $warrantyDTO->correlative,
            article: $article,
            serie_art: $warrantyDTO->serie_art,
            date: $warrantyDTO->date,
            reference_sale: $referenceSale,
            customer: $customer,
            customer_phone: $warrantyDTO->customer_phone,
            customer_email: $warrantyDTO->customer_email,
            failure_description: $warrantyDTO->failure_description,
            observations: $warrantyDTO->observations,
            diagnosis: $warrantyDTO->diagnosis,
            supplier: $supplier,
            entry_guide: $entryGuide,
            contact: $warrantyDTO->contact,
            follow_up_diagnosis: $warrantyDTO->follow_up_diagnosis,
            follow_up_status: $warrantyDTO->follow_up_status,
            solution: $warrantyDTO->solution,
            warranty_status: $warrantyStatus,
            solution_date: $warrantyDTO->solution_date,
            delivery_description: $warrantyDTO->delivery_description,
            delivery_serie_art: $warrantyDTO->delivery_serie_art,
            credit_note_serie: $warrantyDTO->credit_note_serie,
            credit_note_correlative: $warrantyDTO->credit_note_correlative,
            delivery_date: $warrantyDTO->delivery_date,
            dispatch_note_serie: $warrantyDTO->dispatch_note_serie,
            dispatch_note_correlative: $warrantyDTO->dispatch_note_correlative,
            dispatch_note_date: $warrantyDTO->dispatch_note_date
        );
        return $this->warrantyRepository->save($warranty);
    }
}
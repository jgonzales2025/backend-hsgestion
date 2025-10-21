<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PaymentType\Application\UseCases\FindByIdPaymentTypeUseCase;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Sale\Application\DTOs\SaleDTO;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class CreateSaleUseCase
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepository,
    ){}

    public function execute(SaleDTO $saleDTO): ?Sale
    {
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($saleDTO->company_id);

        $userUseCase = new GetUserByIdUseCase($this->userRepository);
        $user = $userUseCase->execute($saleDTO->user_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($saleDTO->branch_id);

        $currencyTypeUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
        $currencyType = $currencyTypeUseCase->execute($saleDTO->currency_type_id);

        $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentTypeRepository);
        $documentType = $documentTypeUseCase->execute($saleDTO->document_type_id);

        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($saleDTO->customer_id);

        $paymentTypeUseCase = new FindByIdPaymentTypeUseCase($this->paymentTypeRepository);
        $paymentType = $paymentTypeUseCase->execute($saleDTO->payment_type_id);

        $sale = new Sale(
            id: 0,
            company: $company,
            documentType: $documentType,
            branch: $branch,
            document_number: $saleDTO->document_number,
            parallel_rate: $saleDTO->parallel_rate,
            customer: $customer,
            date: $saleDTO->date,
            due_date: $saleDTO->due_date,
            days: $saleDTO->days,
            user: $user,
            paymentType: $paymentType,
            observations: $saleDTO->observations,
            currencyType: $currencyType,
            subtotal: $saleDTO->subtotal,
            igv: $saleDTO->igv,
            total: $saleDTO->total,
            status: $saleDTO->status,
            is_locked: $saleDTO->is_locked,
        );

        return $this->saleRepository->save($sale);
    }
}

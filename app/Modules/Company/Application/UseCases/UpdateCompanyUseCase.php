<?php

namespace App\Modules\Company\Application\UseCases;

use App\Modules\Company\Application\DTOS\UpdateCompanyDTO;
use App\Modules\Company\Domain\Entities\UpdateCompany;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;

class UpdateCompanyUseCase
{
    public function __construct(private readonly CompanyRepositoryInterface $companyRepositoryInterface)
    {
    }

    public function execute(int $id, UpdateCompanyDTO $updateCompanyDTO): void
    {
        $company = new UpdateCompany(
            default_currency_type_id: $updateCompanyDTO->default_currency_type_id,
            min_profit: $updateCompanyDTO->min_profit,
            max_profit: $updateCompanyDTO->max_profit,
        );
        
        $this->companyRepositoryInterface->update($id, $company);
    }
}
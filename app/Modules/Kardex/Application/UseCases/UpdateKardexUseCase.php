<?php

namespace App\Modules\Kardex\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Kardex\Application\DTOS\KardexDTO;
use App\Modules\Kardex\Domain\Interface\KardexRepositoryInterface;
use Modules\Kardex\Domain\Entites\Kardex;

class UpdateKardexUseCase
{
    public function __construct(
        private readonly KardexRepositoryInterface $kardexRepository,
        private readonly CompanyRepositoryInterface $companyRepository, 
        private readonly BranchRepositoryInterface $branchRepository){}

    public function execute(KardexDTO $kardex, int $id)
    {
      $usecaseCompany = new FindByIdCompanyUseCase($this->companyRepository);
      $company = $usecaseCompany->execute($kardex->company_id);

      $usecaseBranch = new FindByIdBranchUseCase($this->branchRepository);
      $branch = $usecaseBranch->execute($kardex->branch_id);

        $eloquentkardardex = new Kardex(
            id: $id,
            company: $company,
            branch: $branch,
            codigo: $kardex->codigo,
            is_today: $kardex->is_today,
            description: $kardex->description,
            before_fech: $kardex->before_fech,
            after_fech: $kardex->after_fech,
            status: $kardex->status,
        );
        return $this->kardexRepository->update($eloquentkardardex);
     
    }
}

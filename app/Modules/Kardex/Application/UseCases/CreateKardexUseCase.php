<?php

namespace App\Modules\Kardex\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Kardex\Application\DTOS\KardexDTO;
use App\Modules\Kardex\Domain\Interface\KardexRepositoryInterface;
use Modules\Kardex\Domain\Entites\Kardex;

class CreateKardexUseCase
{
    public function __construct(
        private readonly KardexRepositoryInterface $kardexRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CompanyRepositoryInterface $companyRepository
    ) {}

    public function execute(KardexDTO $data)
    {
        $usecaseBranch = new FindByIdBranchUseCase($this->branchRepository);
        $usecaseBranch = $usecaseBranch->execute($data->branch_id);

        $usecaseCompany = new FindByIdCompanyUseCase($this->companyRepository);
        $usecaseCompany = $usecaseCompany->execute($data->company_id);

        $kardex = new Kardex(
            id: 0,
            company: $usecaseCompany,
            branch: $usecaseBranch,
            codigo: $data->codigo,
            is_today: $data->is_today,
            description: $data->description,
            before_fech: $data->before_fech,
            after_fech: $data->after_fech,
            status: $data->status,
        );
        return $this->kardexRepository->save($kardex);
    }
}

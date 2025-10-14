<?php

namespace App\Modules\Company\Application\UseCases;

use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;

class FindByIdCompanyUseCase{
    private companyRepositoryInterface $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
      {
        $this->companyRepository = $companyRepository;
      }
    public function execute(int $id){
      return $this->companyRepository->findById($id);
    }
}
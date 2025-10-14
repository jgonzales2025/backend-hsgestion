<?php

namespace App\Modules\Company\Application\UseCases;

use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;

class FindAllCompanyUseCase{
    private companyRepositoryInterface $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository){
       $this->companyRepository= $companyRepository;
    }
    public function execute(){
        return $this->companyRepository->findAllCompanys();
    }
    
}

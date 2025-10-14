<?php

namespace App\Modules\CurrencyType\Application\UseCases;

use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;

class FindAllCurrencyTypeUseCase{
    private CurrencyTypeRepositoryInterface $currencyTypeRepository;

    public function __construct(CurrencyTypeRepositoryInterface $currencyTypeRepository){
      $this->currencyTypeRepository = $currencyTypeRepository;
    }

    public function execute(){
        return $this->currencyTypeRepository->findAllCurrencyTy();
    }
}
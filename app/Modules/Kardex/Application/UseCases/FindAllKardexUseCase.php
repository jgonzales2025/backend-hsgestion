<?php

namespace App\Modules\Kardex\Application\UseCases;

use App\Modules\Kardex\Domain\Interface\KardexRepositoryInterface;

class FindAllKardexUseCase
{
    public function __construct(private readonly KardexRepositoryInterface $kardexRepository){}

    public function execute()
    {
        return $this->kardexRepository->getAll();
     
    }
}
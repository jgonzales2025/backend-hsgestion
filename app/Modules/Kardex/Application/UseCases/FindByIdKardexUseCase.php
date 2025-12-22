<?php

namespace App\Modules\Kardex\Application\UseCases;

use App\Modules\Kardex\Domain\Interface\KardexRepositoryInterface;

class FindByIdKardexUseCase
{
    public function __construct(private readonly KardexRepositoryInterface $kardexRepository){}

    public function execute(int $id)
    {
        return $this->kardexRepository->getById($id);
     
    }
}
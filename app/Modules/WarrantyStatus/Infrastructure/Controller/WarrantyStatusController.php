<?php

namespace App\Modules\WarrantyStatus\Infrastructure\Controller;

use App\Modules\WarrantyStatus\Application\UseCases\FindAllWarrantyStatusesUseCases;
use App\Modules\WarrantyStatus\Domain\Interfaces\WarrantyStatusRepositoryInterface;

class WarrantyStatusController
{
    public function __construct(private readonly WarrantyStatusRepositoryInterface $warrantyStatusRepository){}
    public function index()
    {
        $warrantyStatusesUseCase = new FindAllWarrantyStatusesUseCases($this->warrantyStatusRepository);
        $warrantyStatuses = $warrantyStatusesUseCase->execute();

        return response()->json($warrantyStatuses);
    }
}
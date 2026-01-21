<?php

namespace App\Modules\WarrantyStatus\Infrastructure\Controller;

use App\Modules\WarrantyStatus\Application\UseCases\FindAllWarrantyStatusesUseCases;
use App\Modules\WarrantyStatus\Domain\Interfaces\WarrantyStatusRepositoryInterface;
use App\Modules\WarrantyStatus\Infrastructure\Resource\WarrantyStatusResource;
use Illuminate\Http\Request;

class WarrantyStatusController
{
    public function __construct(private readonly WarrantyStatusRepositoryInterface $warrantyStatusRepository){}
    public function index(Request $request)
    {
        $type = $request->input('type');
        $warrantyStatusesUseCase = new FindAllWarrantyStatusesUseCases($this->warrantyStatusRepository);
        $warrantyStatuses = $warrantyStatusesUseCase->execute($type);

        return response()->json(WarrantyStatusResource::collection($warrantyStatuses)->resolve());
    }
}
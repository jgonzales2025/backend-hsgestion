<?php

namespace App\Modules\Ubigeo\Districts\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Ubigeo\Districts\Application\UseCases\FindAllDistrictsUseCases;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;
use App\Modules\Ubigeo\Districts\Infrastructure\Resource\DistrictResource;

class DistrictController extends Controller
{
    public function __construct(private readonly DistrictRepositoryInterface $districtRepository){}

    public function index($coddep, $codpro): array
    {
        $districtUseCase = new FindAllDistrictsUseCases($this->districtRepository);
        $districts = $districtUseCase->execute($coddep, $codpro);

        return DistrictResource::collection($districts)->resolve();
    }
}

<?php

namespace App\Modules\Ubigeo\Provinces\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Ubigeo\Provinces\Application\UseCases\FindAllProvincesUseCase;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;
use App\Modules\Ubigeo\Provinces\Infrastructure\Resources\ProvinceResource;

class ProvinceController extends Controller
{
    public function __construct(private readonly ProvinceRepositoryInterface $provinceRepository){}

    public function index($id): array
    {
        $provincesUseCase = new FindAllProvincesUseCase($this->provinceRepository);
        $provinces = $provincesUseCase->execute($id);

        return ProvinceResource::collection($provinces)->resolve();
    }
}

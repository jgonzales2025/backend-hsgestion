<?php

namespace App\Modules\Provinces\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Provinces\Application\UseCases\FindAllProvincesUseCase;
use App\Modules\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;
use App\Modules\Provinces\Infrastructure\Resources\ProvinceResource;

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

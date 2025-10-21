<?php

namespace App\Modules\Serie\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Serie\Application\UseCases\FindByDocumentTypeUseCase;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\Serie\Infrastructure\Resources\SerieResource;
use Illuminate\Http\JsonResponse;

class SerieController extends Controller
{
    public function __construct(private readonly SerieRepositoryInterface $serieRepository){}

    public function findByDocumentType(int $documentType): array|JsonResponse
    {
        $serieUseCase = new FindByDocumentTypeUseCase($this->serieRepository);
        $series = $serieUseCase->execute($documentType);

        if (!$series) {
            return response()->json(["message" => "No se encontraron series"]);
        }

        return SerieResource::collection($series)->resolve();
    }
}

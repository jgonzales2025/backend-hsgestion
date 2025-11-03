<?php

namespace App\Modules\Serie\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Serie\Application\UseCases\FindByDocumentTypeUseCase;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\Serie\Infrastructure\Resources\SerieResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SerieController extends Controller
{
    public function __construct(private readonly SerieRepositoryInterface $serieRepository){}

    public function findByDocumentType(Request $request): JsonResponse
    {
        $documentType = $request->query('document_type_id');
        $branch_id = $request->query('branch_id');
        $referenceDocumentType = $request->query('reference_document_type_id');

        $serieUseCase = new FindByDocumentTypeUseCase($this->serieRepository);
        $series = $serieUseCase->execute($documentType, $branch_id, $referenceDocumentType);

        if (!$series) {
            return response()->json(["message" => "No se encontraron series"]);
        }

        return response()->json(
            (new SerieResource($series))->resolve(),
        );
    }
}

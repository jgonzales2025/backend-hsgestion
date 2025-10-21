<?php

namespace App\Modules\ReferenceCode\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\RecordType\Infrastructure\Persistence\EloquentRecordTypeRepository;
use App\Modules\ReferenceCode\Application\DTOs\ReferenceCodeDTO;
use App\Modules\ReferenceCode\Application\UseCase\CreateReferenceCodeUseCase;
use App\Modules\ReferenceCode\Application\UseCase\FindAllReferenceCodeUseCase;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCode;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCodeCIAUseCase;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCodeUseCase;
use App\Modules\ReferenceCode\Application\UseCase\UpdateReferenceCodeUseCase;
use App\Modules\ReferenceCode\Domain\Interfaces\ReferenceCodeRepositoryInterface;
use App\Modules\ReferenceCode\Infrastructure\Models\EloquentReferenceCode;
use App\Modules\ReferenceCode\Infrastructure\Persistence\EloquentReferenceCodeRepository;
use App\Modules\ReferenceCode\Infrastructure\Requests\StoreReferenceCodeRequest;
use App\Modules\ReferenceCode\Infrastructure\Requests\UpdateReferenceCodeRequest;
use App\Modules\ReferenceCode\Infrastructure\Resources\ReferenceCodeResource;
use Illuminate\Http\JsonResponse;

class ReferenceCodeController extends Controller
{
    protected EloquentReferenceCodeRepository $referenceCodeRepository;
    protected EloquentArticleRepository $articleRepository;

    public function __construct(EloquentReferenceCodeRepository $referenceCodeRepository, EloquentArticleRepository $articleRepository)
    {
        $this->referenceCodeRepository = $referenceCodeRepository;
        $this->articleRepository = $articleRepository;
    }

    public function index(): JsonResponse
    {
        $referenceCodeUseCase = new FindAllReferenceCodeUseCase($this->referenceCodeRepository);
        $referenceCode = $referenceCodeUseCase->execute();

        return response()->json(ReferenceCodeResource::collection($referenceCode)->resolve(), 200);

    }
    public function show(int $id): JsonResponse
    {

        $referenceCodeUseCase = new FindByIdReferenceCodeUseCase($this->referenceCodeRepository);
        $referenceCode = $referenceCodeUseCase->execute($id);
        if (empty($referenceCode)) {

            return response()->json(['message' => 'No hay datos'], 404);
        }
        return response()->json(
            ReferenceCodeResource::collection($referenceCode)->resolve(),
            200
        );

    }
    public function indexid(int $id): JsonResponse
    {
        $referenceCodeUseCase = new FindByIdReferenceCodeCIAUseCase($this->referenceCodeRepository);
        $referenceCode = $referenceCodeUseCase->execute($id);

        if (empty($referenceCode)) {
            return response()->json(['message' => 'No se encontro Sucursal'], 404);
        }

        return response()->json(
            (new ReferenceCodeResource($referenceCode))->resolve(),
            200
        );

    }

    public function store(StoreReferenceCodeRequest $request, $id): JsonResponse
    {
        $filter = new FindByIdArticleUseCase($this->articleRepository);
        $result = $filter->execute($id);
        if (!$result) {
            return response()->json(["message" => "No existe este articulo"], 404);
        }

        $referenceCodeDTO = new ReferenceCodeDTO($request->validated());
        $referenceCodeUseCase = new CreateReferenceCodeUseCase($this->referenceCodeRepository);
        $referenceCode = $referenceCodeUseCase->execute($id, $referenceCodeDTO);

        return response()->json(new ReferenceCodeResource($referenceCode), 201);
    }


    public function update(UpdateReferenceCodeRequest $request, $id): JsonResponse
    {
        $referenceCodeDTO = new ReferenceCodeDTO($request->validated());
        $referenceCodeUseCase = new UpdateReferenceCodeUseCase($this->referenceCodeRepository);
        $referenceCodeUseCase->execute($id, $referenceCodeDTO);

        if (empty($referenceCodeUseCase)) {
            return response()->json(["message" => "No hay datos"], 404);
        }

        return response()->json(['message' => 'codigo de referencia actualizado']);
    }

}
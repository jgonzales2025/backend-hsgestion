<?php

namespace App\Modules\ReferenceCode\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ReferenceCode\Application\DTOs\ReferenceCodeDTO;
use App\Modules\ReferenceCode\Application\UseCase\FindAllReferenceCodeUseCase;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCode;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCodeUseCase;
use App\Modules\ReferenceCode\Application\UseCase\UpdateReferenceCodeUseCase;
use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;
use App\Modules\ReferenceCode\Infrastructure\Persistence\EloquentReferenceCodeRepository;
use App\Modules\ReferenceCode\Infrastructure\Requests\StoreReferenceCodeRequest;
use App\Modules\ReferenceCode\Infrastructure\Requests\UpdateReferenceCodeRequest;
use App\Modules\ReferenceCode\Infrastructure\Resources\ReferenceCodeResource;
use Illuminate\Http\JsonResponse;

class ReferenceCodeController extends Controller
{

    protected $referenceCodeRepository;
    // protected $referenceCodeRepositoryinterface;
    public function __construct()
    {
        $this->referenceCodeRepository = new EloquentReferenceCodeRepository;
        //    $this->referenceCodeRepositoryinterface = new EloquentReferenceCodeRepository();
    }
    public function index(): array
    {
        $referenceCodeUseCase = new FindAllReferenceCodeUseCase($this->referenceCodeRepository);
        $referenceCode = $referenceCodeUseCase->execute();

        return ReferenceCodeResource::collection($referenceCode)->resolve();

    }
    public function show(int $id): array
    {
        $referenceCodeUseCase = new FindByIdReferenceCodeUseCase($this->referenceCodeRepository);
        $referenceCodes = $referenceCodeUseCase->execute($id);
        if (empty($referenceCodes)) {
            return [];
        }

        return ReferenceCodeResource::collection($referenceCodes)->resolve();

    }
    public function indexid(int $id): JsonResponse
    {
        $branches = $this->referenceCodeRepository->indexid($id);

        if (empty($branches)) {
            return response()->json(['error' => 'No se encontro Sucursal'], 404);
        }

        return response()->json(
            (new ReferenceCodeResource($branches))->resolve(),
            200
        );

    }


    public function store(StoreReferenceCodeRequest $request, $id): JsonResponse
    {
    //     1️⃣ Crear la entidad de dominio a partir del request
    //       $branches = $this->referenceCodeRepository->findById($id);
    //       if (!$branches) {
    // return response()->json(['message' => 'no hay wualterrrrrrr'],404) ;
  
    //       }
        $referenceCode = new ReferenceCode(
            id: 0,
            refCode: $request->input('refCode') , 
            articleId: $id,
            dateAt: now()->toDateTimeString(),
            status: $request->input('status') ?? 1
        );


        // 2️⃣ Guardar en repositorio
        $savedReferenceCode = $this->referenceCodeRepository->save($referenceCode);

        // 3️⃣ Devolver respuesta JSON
        return response()->json(new ReferenceCodeResource($savedReferenceCode), 201);
    }


    public function update(UpdateReferenceCodeRequest $request, $id): JsonResponse
    {    
        $referenceCodeDTO = new ReferenceCodeDTO($request->validated());
        $referenceCodeUseCase = new UpdateReferenceCodeUseCase($this->referenceCodeRepository);
       $referenceCodeUseCase->execute($id,$referenceCodeDTO);

        return response()->json(['message'=>'codigo de referencia actualizado']);
    }

}
<?php

namespace App\Modules\ReferenceCode\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ReferenceCode\Application\UseCase\FindAllReferenceCodeUseCase;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCode;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCodeUseCase;
use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;
use App\Modules\ReferenceCode\Infrastructure\Persistence\EloquentReferenceCodeRepository;
use App\Modules\ReferenceCode\Infrastructure\Requests\StoreReferenceCodeRequest;
use App\Modules\ReferenceCode\Infrastructure\Resources\ReferenceCodeResource;
use Illuminate\Http\JsonResponse;

class ReferenceCodeController extends Controller{
    
    protected $referenceCodeRepository;
    // protected $referenceCodeRepositoryinterface;
    public function __construct(){
       $this->referenceCodeRepository =new EloquentReferenceCodeRepository;
    //    $this->referenceCodeRepositoryinterface = new EloquentReferenceCodeRepository();
    }
    public function index():array{
        $referenceCodeUseCase = new FindAllReferenceCodeUseCase($this->referenceCodeRepository);
        $referenceCode = $referenceCodeUseCase->execute();
        
        return ReferenceCodeResource::collection($referenceCode)->resolve();
    }
     public function indexid(int $id):JsonResponse{
       $branches = $this->referenceCodeRepository->indexid($id);

      if (empty($branches)) {
        return response()->json(['error' => 'No se encontro Sucursal'], 404);
    }

  return response()->json(
    (new ReferenceCodeResource($branches))->resolve(),
    200
);

     }


public function store(StoreReferenceCodeRequest $request): JsonResponse
{
    // 1️⃣ Crear la entidad de dominio a partir del request
    $referenceCode = new ReferenceCode(
        id: 0,
        refCode: $request->input('ref_code') ?? 'REF-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT), // ✅ snake_case
        articleId: $request->input('article_id'), // ✅ snake_case
        dateAt: now()->toDateTimeString(),
        status: $request->input('status') ?? 1
    );

    // 2️⃣ Guardar en repositorio
    $savedReferenceCode = $this->referenceCodeRepository->save($referenceCode);

    // 3️⃣ Devolver respuesta JSON
    return response()->json(new ReferenceCodeResource($savedReferenceCode), 201);
}


        public function update(int $id):JsonResponse{
         $referenceCodeUseCase = new FindByIdReferenceCodeUseCase($this->referenceCodeRepository);
         $referenceCode = $referenceCodeUseCase->execute($id);

             return response()->json(
            (new ReferenceCodeResource($referenceCode))->resolve(),200
        );
    }

}
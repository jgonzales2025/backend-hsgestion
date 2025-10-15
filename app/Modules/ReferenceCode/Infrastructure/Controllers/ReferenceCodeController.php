<?php

namespace App\Modules\ReferenceCode\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ReferenceCode\Application\UseCase\FindAllReferenceCodeUseCase;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCode;
use App\Modules\ReferenceCode\Application\UseCase\FindByIdReferenceCodeUseCase;
use App\Modules\ReferenceCode\Infrastructure\Persistence\EloquentReferenceCodeRepository;
use App\Modules\ReferenceCode\Infrastructure\Resources\ReferenceCodeResource;
use Illuminate\Http\JsonResponse;

class ReferenceCodeController extends Controller{
    
    protected $referenceCodeRepository;
    public function __construct(){
       $this->referenceCodeRepository =new EloquentReferenceCodeRepository;
    }
    public function index():array{
        $referenceCodeUseCase = new FindAllReferenceCodeUseCase($this->referenceCodeRepository);
        $referenceCode = $referenceCodeUseCase->execute();
        
        return ReferenceCodeResource::collection($referenceCode)->resolve();
    }

    public function show(int $id):JsonResponse{
         $referenceCodeUseCase = new FindByIdReferenceCodeUseCase($this->referenceCodeRepository);
         $referenceCode = $referenceCodeUseCase->execute($id);

            return response()->json(
            (new ReferenceCodeResource($referenceCode))->resolve(),200
        );
        
    }
        public function update(int $id):JsonResponse{
         $referenceCodeUseCase = new FindByIdReferenceCodeUseCase($this->referenceCodeRepository);
         $referenceCode = $referenceCodeUseCase->execute($id);

             return response()->json(
            (new ReferenceCodeResource($referenceCode))->resolve(),200
        );
    }

}
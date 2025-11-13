<?php

namespace App\Modules\Purchases\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Purchases\Application\DTOS\PurchaseDTO;
use App\Modules\Purchases\Application\UseCases\CreatePurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\FindAllPurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\FindByIdPurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\UpdatePurchaseUseCase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Request\CreatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Request\PudatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Resource\PurchaseResource;
use Illuminate\Http\JsonResponse;
use Js;

class PurchaseController extends Controller{
    public function __construct(private readonly PurchaseRepositoryInterface $purchaseRepository)
    {
    }
    public function index():array{
        $findAllPurchaseUseCase = new FindAllPurchaseUseCase($this->purchaseRepository);
        $purchases =  $findAllPurchaseUseCase->execute();
        
        return PurchaseResource::collection($purchases)->resolve();
    
    }
    public function show(int $id):JsonResponse{
     $findByIdPurchaseUseCase = new FindByIdPurchaseUseCase($this->purchaseRepository);
     $purchase = $findByIdPurchaseUseCase->execute($id);

     return response()->json(
        new PurchaseResource($purchase),200
     );

    }
    public function store(CreatePurchaseRequest $request):JsonResponse{
        $purchaseDTO = new PurchaseDTO($request->resolved());
        $cretaePurchaseUseCase = new CreatePurchaseUseCase($this->purchaseRepository);
        $purchase = $cretaePurchaseUseCase->execute($purchaseDTO);

        return response()->json(
            new PurchaseResource($purchase),201
        );
    }
    public function update(PudatePurchaseRequest $request,int $id):JsonResponse{
       $purchaseDTO = new PurchaseDTO($request->resolved());
       $updatePurchaseUseCase = new UpdatePurchaseUseCase($this->purchaseRepository);
       $purchase = $updatePurchaseUseCase->execute($purchaseDTO,$id);

       return response()->json(
        new PurchaseResource($purchase),201
       );
    }
  
}
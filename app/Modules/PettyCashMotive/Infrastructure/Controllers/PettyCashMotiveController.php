<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PettyCashMotive\Application\DTOS\PettyCashMotiveDTO;
use App\Modules\PettyCashMotive\Application\UseCases\CreatePettyCashMotive;
use App\Modules\PettyCashMotive\Application\UseCases\FindAllPettyCashMotive;
use App\Modules\PettyCashMotive\Application\UseCases\FindByIdPettyCashMotive;
use App\Modules\PettyCashMotive\Application\UseCases\UpdatePettyCashMotiveUseCase;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;
use App\Modules\PettyCashMotive\Infrastructure\Request\CreatePettyCashMotiveRequest;
use App\Modules\PettyCashMotive\Infrastructure\Request\UpdatePettyCashMotiveRequest;
use App\Modules\PettyCashMotive\Infrastructure\Resource\PettyCashMotiveResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PettyCashMotiveController extends Controller{
    public function __construct(private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveInterfaceRepository){}

    public function index(Request $request):array{

        $receipt_type = $request->query('receipt_type');

        $finAllPettyCashMotive = new FindAllPettyCashMotive($this->pettyCashMotiveInterfaceRepository);
        $pettyCashMotives = $finAllPettyCashMotive->execute($receipt_type);

        return PettyCashMotiveResource::collection($pettyCashMotives)->resolve();
    
    }
    public function store(CreatePettyCashMotiveRequest $request):JsonResponse
       {
        $pettyCashMotiveDTO = new PettyCashMotiveDTO($request->validated());
        $createPettyCashMotive = new CreatePettyCashMotive($this->pettyCashMotiveInterfaceRepository);
        $pettyCashMotive = $createPettyCashMotive->execute($pettyCashMotiveDTO);

        return response()->json(
            new PettyCashMotiveResource($pettyCashMotive),201
        );
    }
    public function show(int $id){
       
        $findByIdPettyCashMotive = new FindByIdPettyCashMotive($this->pettyCashMotiveInterfaceRepository);
        $pettyCashMotive  = $findByIdPettyCashMotive->execute($id);

        return response()->json(
            new PettyCashMotiveResource($pettyCashMotive),200
        );
    }
   public function update(int $id,UpdatePettyCashMotiveRequest $request):JsonResponse{
 $pettyCashMotiveDTO = new PettyCashMotiveDTO($request->validated());
      $updatePettyCashMotive = new UpdatePettyCashMotiveUseCase($this->pettyCashMotiveInterfaceRepository);
      $pettyCashMotive = $updatePettyCashMotive->execute($pettyCashMotiveDTO,$id);
   
      return response()->json(
         new PettyCashMotiveResource($pettyCashMotive),200
      );
   
      
}
}
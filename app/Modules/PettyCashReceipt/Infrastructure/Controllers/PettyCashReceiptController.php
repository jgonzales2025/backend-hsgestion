<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PettyCashReceipt\Application\DTOS\PettyCashReceiptDTO;
use App\Modules\PettyCashReceipt\Application\UseCases\CreatePettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\FindAllPettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\UpdatePettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;
use App\Modules\PettyCashReceipt\Infrastructure\Request\CreatePettyCashReceiptRequest;
use App\Modules\PettyCashReceipt\Infrastructure\Request\UpdatePettyCashReceiptRequest;
use App\Modules\PettyCashReceipt\Infrastructure\Resource\PettyCashReceiptResource;

class PettyCashReceiptController extends Controller
{

    public function __construct(private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository)
    {
    }
    public function index(): array
    {
        $pettyCashReceiptsUseCase = new FindAllPettyCashReceiptUseCase($this->pettyCashReceiptRepository);
        $pettyCashReceipts = $pettyCashReceiptsUseCase->execute();

        return PettyCashReceiptResource::collection($pettyCashReceipts)->resolve();

    }
    public function store(CreatePettyCashReceiptRequest $request)
    {
        $eloquentCreatePettyCash = new PettyCashReceiptDTO($request->validated());

        $eloquentCreatePettyCashReceiptUseCase = new CreatePettyCashReceiptUseCase($this->pettyCashReceiptRepository);
        $eloquentCreatePettyCash = $eloquentCreatePettyCashReceiptUseCase->execute($eloquentCreatePettyCash);

         return response()->json(new PettyCashReceiptResource($eloquentCreatePettyCash), 201);

    }
    public function update(int $id, UpdatePettyCashReceiptRequest $request)
    {
        $eloquentCreatePettyCash = new PettyCashReceiptDTO($request->validated());

        $updatePettyCashReceiptUseCase = new UpdatePettyCashReceiptUseCase($this->pettyCashReceiptRepository);
        $updatePettyCashReceipt =  $updatePettyCashReceiptUseCase->execute($eloquentCreatePettyCash, $id);
        
        return response()->json(new PettyCashReceiptResource($updatePettyCashReceipt), 200);
    
    }
}
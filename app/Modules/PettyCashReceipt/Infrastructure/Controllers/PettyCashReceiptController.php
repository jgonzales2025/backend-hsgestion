<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\PettyCashReceipt\Application\DTOS\PettyCashReceiptDTO;
use App\Modules\PettyCashReceipt\Application\UseCases\CreatePettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\FindAllPettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\FindByIdPettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\UpdatePettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;
use App\Modules\PettyCashReceipt\Infrastructure\Request\CreatePettyCashReceiptRequest;
use App\Modules\PettyCashReceipt\Infrastructure\Request\UpdatePettyCashReceiptRequest;
use App\Modules\PettyCashReceipt\Infrastructure\Resource\PettyCashReceiptResource;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PettyCashReceiptController extends Controller
{

    public function __construct(
        private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
           private readonly DocumentNumberGeneratorService $documentNumberGeneratorService
    ) {
    }
    public function index(Request $request): array
    {
       $filter = $request->query('filter');

        $pettyCashReceiptsUseCase = new FindAllPettyCashReceiptUseCase($this->pettyCashReceiptRepository);
        $pettyCashReceipts = $pettyCashReceiptsUseCase->execute($filter);

        return PettyCashReceiptResource::collection($pettyCashReceipts)->resolve();

    }
    public function show(int $id): JsonResponse
    {
        $pettyCashReceiptUseCase = new FindByIdPettyCashReceiptUseCase($this->pettyCashReceiptRepository);
        $pettyCashReceipt = $pettyCashReceiptUseCase->execute($id);

        return response()->json(new PettyCashReceiptResource($pettyCashReceipt), 200);

    }
    public function store(CreatePettyCashReceiptRequest $request)
    {
        $eloquentCreatePettyCash = new PettyCashReceiptDTO($request->validated());

        $eloquentCreatePettyCashReceiptUseCase = new CreatePettyCashReceiptUseCase(
            $this->pettyCashReceiptRepository,
            $this->branchRepository,
            $this->currencyTypeRepository,
            $this->documentNumberGeneratorService
        );
        $eloquentCreatePettyCash = $eloquentCreatePettyCashReceiptUseCase->execute($eloquentCreatePettyCash);

        return response()->json(new PettyCashReceiptResource($eloquentCreatePettyCash), 201);

    }
    public function update(int $id, UpdatePettyCashReceiptRequest $request)
    {
        $eloquentCreatePettyCash = new PettyCashReceiptDTO($request->validated());

        $updatePettyCashReceiptUseCase = new UpdatePettyCashReceiptUseCase(
            $this->pettyCashReceiptRepository,
            $this->branchRepository,
            $this->currencyTypeRepository
        );
        $updatePettyCashReceipt = $updatePettyCashReceiptUseCase->execute($eloquentCreatePettyCash, $id);

        return response()->json(new PettyCashReceiptResource($updatePettyCashReceipt), 200);

    }
}
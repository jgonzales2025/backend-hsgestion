<?php

namespace App\Modules\Collections\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Collections\Application\DTOs\CollectionDTO;
use App\Modules\Collections\Application\UseCases\CreateCollectionUseCase;
use App\Modules\Collections\Application\UseCases\FindAllCollectionsUseCase;
use App\Modules\Collections\Application\UseCases\FindBySaleIdCollectionUseCase;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;
use App\Modules\Collections\Infrastructure\Requests\StoreCollectionRequest;
use App\Modules\Collections\Infrastructure\Resources\CollectionResource;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CollectionController extends Controller
{
    public function __construct(
        private readonly CollectionRepositoryInterface $collectionRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
        private readonly TransactionLogRepositoryInterface $transactionLogRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly BranchRepositoryInterface $branchRepository,
    ){}

    public function index(): array
    {
        $collectionUseCase = new FindAllCollectionsUseCase($this->collectionRepository);
        $collections = $collectionUseCase->execute();

        return CollectionResource::collection($collections)->resolve();
    }

    public function store(StoreCollectionRequest $request): JsonResponse
    {
        $userId = request()->get('user_id');
        $role = request()->get('role');
        $companyId = request()->get('company_id');

        $collectionDTO = new CollectionDTO($request->validated());
        $collectionUseCase = new CreateCollectionUseCase($this->collectionRepository, $this->paymentMethodRepository);
        $collection = $collectionUseCase->execute($collectionDTO);

        $transactionLogs = new CreateTransactionLogUseCase($this->transactionLogRepository, $this->userRepository, $this->companyRepository, $this->documentTypeRepository, $this->branchRepository);
        $transactionDTO = new TransactionLogDTO([
            'user_id' => $userId,
            'role_name' => $role,
            'description_log' => 'Cobranza',
            'action' => $request->method(),
            'company_id' => $companyId,
            'branch_id' => (int) substr($collection->getSaleSerie(), -1),
            'document_type_id' => $collection->getSaleDocumentTypeId(),
            'serie' => $collection->getSaleSerie(),
            'correlative' => $collection->getSaleCorrelative(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $transactionLogs->execute($transactionDTO);

        return response()->json((new CollectionResource($collection))->resolve(), 201);
    }

    public function showBySaleId(int $id): JsonResponse
    {
        $collectionUseCase = new FindBySaleIdCollectionUseCase($this->collectionRepository);
        $collection = $collectionUseCase->execute($id);

        return response()->json(
            CollectionResource::collection($collection)->resolve(),
             200
        );
    }
}

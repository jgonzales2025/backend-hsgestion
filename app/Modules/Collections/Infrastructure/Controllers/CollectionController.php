<?php

namespace App\Modules\Collections\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Advance\Application\DTOs\AdvanceDTO;
use App\Modules\Advance\Application\UseCases\CreateAdvanceUseCase;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Collections\Application\DTOs\BulkCollectionDTO;
use App\Modules\Collections\Application\DTOs\CollectionCreditNoteDTO;
use App\Modules\Collections\Application\DTOs\CollectionDTO;
use App\Modules\Collections\Application\UseCases\CancelChargeCollectionUseCase;
use App\Modules\Collections\Application\UseCases\CreateCollectionCreditNoteUseCase;
use App\Modules\Collections\Application\UseCases\CreateCollectionUseCase;
use App\Modules\Collections\Application\UseCases\FindAllCollectionsUseCase;
use App\Modules\Collections\Application\UseCases\FindByIdCollectionUseCase;
use App\Modules\Collections\Application\UseCases\FindBySaleIdCollectionUseCase;
use App\Modules\Collections\Application\UseCases\StoreBulkCollectionUseCase;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;
use App\Modules\Collections\Infrastructure\Requests\StoreBulkCollectionRequest;
use App\Modules\Collections\Infrastructure\Requests\StoreCollectionCreditNoteRequest;
use App\Modules\Collections\Infrastructure\Requests\StoreCollectionRequest;
use App\Modules\Collections\Infrastructure\Requests\StoreMasiveCollectionRequest;
use App\Modules\Collections\Infrastructure\Resources\CollectionResource;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly AdvanceRepositoryInterface $advanceRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly BankRepositoryInterface $bankRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
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
            'observations' => 'Cobranza registrada.',
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

    public function storeCollectionCreditNote(StoreCollectionCreditNoteRequest $request): JsonResponse
    {
        $userId = request()->get('user_id');
        $role = request()->get('role');
        $companyId = request()->get('company_id');

        $collectionDTO = new CollectionCreditNoteDTO($request->validated());
        $collectionCreditNoteUseCase = new CreateCollectionCreditNoteUseCase($this->collectionRepository, $this->paymentMethodRepository, $this->saleRepository);
        $collectionCreditNote = $collectionCreditNoteUseCase->execute($collectionDTO);

        $transactionLogs = new CreateTransactionLogUseCase($this->transactionLogRepository, $this->userRepository, $this->companyRepository, $this->documentTypeRepository, $this->branchRepository);
        $transactionDTO = new TransactionLogDTO([
            'user_id' => $userId,
            'role_name' => $role,
            'description_log' => 'Nota de Credito',
            'observations' => 'Nota de credito registrada por cobranza.',
            'action' => $request->method(),
            'company_id' => $companyId,
            'branch_id' => (int) substr($collectionCreditNote->getSaleSerie(), -1),
            'document_type_id' => $collectionCreditNote->getSaleDocumentTypeId(),
            'serie' => $collectionCreditNote->getSaleSerie(),
            'correlative' => $collectionCreditNote->getSaleCorrelative(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        $transactionLogs->execute($transactionDTO);

        return response()->json((new CollectionResource($collectionCreditNote))->resolve(), 201);
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

    public function cancelCharge(int $id): JsonResponse
    {
        $userId = request()->get('user_id');
        $role = request()->get('role');
        $companyId = request()->get('company_id');

        $collectionUseCase = new CancelChargeCollectionUseCase($this->collectionRepository);
        $collectionUseCase->execute($id);

        $collectionByIdUseCase = new FindByIdCollectionUseCase($this->collectionRepository);
        $collection = $collectionByIdUseCase->execute($id);

        $transactionLogs = new CreateTransactionLogUseCase($this->transactionLogRepository, $this->userRepository, $this->companyRepository, $this->documentTypeRepository, $this->branchRepository);
        $transactionDTO = new TransactionLogDTO([
            'user_id' => $userId,
            'role_name' => $role,
            'description_log' => 'Anulacion de Cobranza',
            'observations' => 'Cobranza anulada.',
            'action' => request()->method(),
            'company_id' => $companyId,
            'branch_id' => (int) substr($collection->getSaleSerie(), -1),
            'document_type_id' => $collection->getSaleDocumentTypeId(),
            'serie' => $collection->getSaleSerie(),
            'correlative' => $collection->getSaleCorrelative(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $transactionLogs->execute($transactionDTO);

        return response()->json(['message' => 'Cobranza anulada con exito'], 200);
    }

    public function storeBulkCollection(StoreBulkCollectionRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $userId = request()->get('user_id');
            $role = request()->get('role');
            $companyId = request()->get('company_id');

            if(isset($request->validated()['advance_amount']) and $request->validated()['advance_amount'] > 0){
                $advanceDTO = new AdvanceDTO([
                    'customer_id' => $request->validated()['customer_id'],
                    'payment_method_id' => $request->validated()['payment_method_id'],
                    'bank_id' => $request->validated()['bank_id'],
                    'operation_number' => $request->validated()['operation_number'],
                    'operation_date' => $request->validated()['operation_date'],
                    'parallel_rate' => $request->validated()['parallel_rate'],
                    'currency_type_id' => $request->validated()['currency_type_id'],
                    'amount' => $request->validated()['advance_amount'],
                ]);
                $advanceUseCase = new CreateAdvanceUseCase($this->advanceRepository, $this->customerRepository, $this->paymentMethodRepository, $this->bankRepository, $this->currencyTypeRepository, $this->documentNumberGeneratorService);
                $advanceUseCase->execute($advanceDTO);
            }

            $bulkCollectionDTO = new BulkCollectionDTO($request->validated());
            $bulkCollectionUseCase = new StoreBulkCollectionUseCase($this->collectionRepository);
            $bulkCollectionUseCase->execute($bulkCollectionDTO, $request->validated()['collections']);

            $transactionLogs = new CreateTransactionLogUseCase($this->transactionLogRepository, $this->userRepository, $this->companyRepository, $this->documentTypeRepository, $this->branchRepository);
            
            foreach($request->validated()['collections'] as $collection){
                $transactionDTO = new TransactionLogDTO([
                    'user_id' => $userId,
                    'role_name' => $role,
                    'description_log' => 'Cobranza masiva',
                    'observations' => 'Cobranza masiva registrada.',
                    'action' => request()->method(),
                    'company_id' => $companyId,
                    'branch_id' => (int) substr($collection['serie'], -1),
                    'document_type_id' => $collection['sale_document_type_id'],
                    'serie' => $collection['serie'],
                    'correlative' => $collection['correlative'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                $transactionLogs->execute($transactionDTO); 
            }
            
            return response()->json(['message' => 'Cobranza masiva guardada con exito'], 201);
        });
        
    }
}

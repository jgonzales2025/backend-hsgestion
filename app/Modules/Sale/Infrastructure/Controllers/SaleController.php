<?php

namespace App\Modules\Sale\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Resource\DispatchNoteResource;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\EntryItemSerial\Application\UseCases\UpdateStatusBySerialsUseCase as UseCasesUpdateStatusBySerialsUseCase;
use App\Modules\EntryItemSerial\Application\UseCases\UpdateStatusBySerialUseCase;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\Installment\Application\DTOs\InstallmentDTO;
use App\Modules\Installment\Application\UseCases\CreateInstallmentUseCase;
use App\Modules\Installment\Application\UseCases\DeleteInstallmentUseCase;
use App\Modules\Installment\Application\UseCases\FindInstallmentBySaleIdUseCase;
use App\Modules\Installment\Domain\Interface\InstallmentRepositoryInterface;
use App\Modules\NoteReason\Domain\Interfaces\NoteReasonRepositoryInterface;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Sale\Application\DTOs\SaleCreditNoteDTO;
use App\Modules\Sale\Application\DTOs\SaleDTO;
use App\Modules\Sale\Application\UseCases\CreateSaleCreditNoteUseCase;
use App\Modules\Sale\Application\UseCases\CreateSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindAllDocumentsByCustomerIdUseCase;
use App\Modules\Sale\Application\UseCases\FindAllNoteCreditsByCustomerUseCase;
use App\Modules\Sale\Application\UseCases\FindAllPendingSalesByCustomerIdUseCase;
use App\Modules\Sale\Application\UseCases\FindAllProformasUseCase;
use App\Modules\Sale\Application\UseCases\FindAllSalesUseCase;
use App\Modules\Sale\Application\UseCases\FindByDocumentReferenceUseCase;
use App\Modules\Sale\Application\UseCases\FindSaleWithUpdatedQuantitiesUseCase;
use App\Modules\Sale\Application\UseCases\FindByDocumentSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindByIdSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindCreditNoteByIdUseCase;
use App\Modules\Sale\Application\UseCases\UpdateCreditNoteUseCase;
use App\Modules\Sale\Application\UseCases\UpdateSaleUseCase;
use App\Modules\Sale\Application\UseCases\UpdateStatusSalesUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\Sale\Infrastructure\Requests\StoreSaleCreditNoteRequest;
use App\Modules\Sale\Infrastructure\Requests\StoreSaleRequest;
use App\Modules\Sale\Infrastructure\Requests\UpdateSaleCreditNoteRequest;
use App\Modules\Sale\Infrastructure\Requests\UpdateSaleRequest;
use App\Modules\Sale\Infrastructure\Resources\DocumentByCustomerResource;
use App\Modules\Sale\Infrastructure\Resources\SaleCreditNoteResource;
use App\Modules\Sale\Infrastructure\Resources\SaleResource;
use App\Modules\SaleArticle\Application\DTOs\SaleArticleDTO;
use App\Modules\SaleArticle\Application\UseCases\CreateSaleArticleUseCase;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;
use App\Modules\SaleArticle\Infrastructure\Resources\SaleArticleCreditNoteResource;
use App\Modules\SaleArticle\Infrastructure\Resources\SaleArticleResource;
use App\Modules\SaleItemSerial\Application\DTOs\SaleItemSerialDTO;
use App\Modules\SaleItemSerial\Application\UseCases\CreateSaleItemSerialUseCase;
use App\Modules\SaleItemSerial\Application\UseCases\DeleteSaleItemSerialBySaleIdUseCase;
use App\Modules\SaleItemSerial\Application\UseCases\FindSerialsInactiveBySaleIdUseCase;
use App\Modules\SaleItemSerial\Application\UseCases\UpdateStatusBySerialsUseCase;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Application\UseCases\FindByDocumentUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use App\Services\SalesSunatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepository,
        private readonly SaleArticleRepositoryInterface $saleArticleRepository,
        private readonly TransactionLogRepositoryInterface $transactionLogRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly NoteReasonRepositoryInterface $noteReasonRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly SaleItemSerialRepositoryInterface $saleItemSerialRepository,
        private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepository,
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly DispatchNotesRepositoryInterface $dispatchNoteRepository,
        private readonly InstallmentRepositoryInterface $installmentRepository,
        private readonly DispatchArticleRepositoryInterface $dispatchNoteArticleRepository,
        private readonly DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
        private readonly SalesSunatService $salesSunatService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $companyId = request()->get('company_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $description = $request->query('description');
        $status = $request->query('status') !== null ? $request->query('status') : null;
        $paymentStatus = $request->query('payment_status') !== null ? $request->query('payment_status') : null;
        $documentTypeId = $request->query('document_type_id');

        $saleUseCase = new FindAllSalesUseCase($this->saleRepository);
        $sales = $saleUseCase->execute($companyId, $startDate, $endDate, $description, $status, $paymentStatus, $documentTypeId);

        $result = [];
        foreach ($sales as $sale) {
            $articles = $this->saleArticleRepository->findBySaleId($sale->getId());
            $serialsByArticle = $this->saleItemSerialRepository->findSerialsBySaleId($sale->getId());

            $articlesWithSerials = array_map(function ($article) use ($serialsByArticle) {
                $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
                return $article;
            }, $articles);

            $result[] = [
                'sale' => (new SaleResource($sale))->resolve(),
                'articles' => SaleArticleResource::collection($articlesWithSerials)->resolve(),
            ];
        }

        return response()->json([
            'data' => $result,
            'current_page' => $sales->currentPage(),
            'per_page' => $sales->perPage(),
            'total' => $sales->total(),
            'last_page' => $sales->lastPage(),
            'next_page_url' => $sales->nextPageUrl(),
            'prev_page_url' => $sales->previousPageUrl(),
            'first_page_url' => $sales->url(1),
            'last_page_url' => $sales->url($sales->lastPage()),
        ]);
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {

            $date = now()->format('Y-m-d');
            if ($request['date'] != $date)
            {
                return new JsonResponse(['message' => 'Solo se pueden emitir ventas con la fecha actual.'], 400);
            }

            $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
            $customer = $customerUseCase->execute($request->validated()['customer_id']);

            if ($customer->getCustomerDocumentType()->getId() == 3 && $request->validated()['document_type_id'] == 1) {
                return new JsonResponse(['message' => 'No se puede emitir una factura a un cliente con DNI'], 400);
            }

            $saleDTO = new SaleDTO($request->validated());
            $saleUseCase = new CreateSaleUseCase($this->saleRepository, $this->companyRepository, $this->branchRepository, $this->userRepository, $this->currencyTypeRepository, $this->documentTypeRepository, $this->customerRepository, $this->paymentTypeRepository, $this->documentNumberGeneratorService, $this->paymentMethodRepository);
            $sale = $saleUseCase->execute($saleDTO);

            if (!empty($request->validated()['installments'])) {
                foreach ($request->validated()['installments'] as $installmentData) {
                    $installmentDTO = new InstallmentDTO([
                        'sale_id' => $sale->getId(),
                        'installment_number' => $installmentData['installment_number'],
                        'amount' => $installmentData['amount'],
                        'due_date' => $installmentData['due_date'],
                    ]);
                    $installmentUseCase = new CreateInstallmentUseCase($this->installmentRepository);
                    $installmentUseCase->execute($installmentDTO);
                }
            }

            $saleArticles = $this->createSaleArticles($sale, $request->validated()['sale_articles']);

            $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
            $sale = $saleUseCase->execute($sale->getId());
            $this->logTransaction($request, $sale);

            return response()->json([
                'sale' => (new SaleResource($sale))->resolve(),
                'articles' => SaleArticleResource::collection($saleArticles)->resolve()
            ], 201);
        });
    }

    public function storeCreditNote(StoreSaleCreditNoteRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $saleCreditNoteDTO = new SaleCreditNoteDTO($request->validated());
            $saleCreditNoteUseCase = new CreateSaleCreditNoteUseCase($this->saleRepository, $this->companyRepository, $this->branchRepository, $this->userRepository, $this->currencyTypeRepository, $this->documentTypeRepository, $this->customerRepository, $this->paymentTypeRepository, $this->noteReasonRepository, $this->documentNumberGeneratorService);
            $saleCreditNote = $saleCreditNoteUseCase->execute($saleCreditNoteDTO);
            
            $validated = $request->validated();
            if ($validated['document_type_id'] === 7 && !in_array($validated['note_reason_id'], [2, 4, 5, 6, 7, 8, 11, 12, 13])) {
                foreach($validated['sale_articles'] as $article) {
                    if (!empty($article['serie'])){
                        $saleItemSerialUseCase = new UpdateStatusBySerialsUseCase($this->saleItemSerialRepository);
                        $saleItemSerialUseCase->execute($article['serie']);
                        
                        $entryItemSerialUseCase = new UseCasesUpdateStatusBySerialsUseCase($this->entryItemSerialRepository);
                        $entryItemSerialUseCase->execute($article['serie']);
                    }
                }
            }

            $saleArticles = $this->createSaleArticles($saleCreditNote, $request->validated()['sale_articles']);
            $this->logTransaction($request, $saleCreditNote);

            return response()->json(
                [
                    'sale' => (new SaleCreditNoteResource($saleCreditNote))->resolve(),
                    'articles' => SaleArticleResource::collection($saleArticles)->resolve()
                ],
                201
            );
        });
    }

    public function show($id): JsonResponse
    {
        $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($id);

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        $result = DB::select('CALL sp_bloqueo_diario(?, ?)', [
            $sale->getDate(),
            3
        ]);

        $bloqueado = $result[0]->bloqueado;

        $installmentsUseCase = new FindInstallmentBySaleIdUseCase($this->installmentRepository);
        $installments = $installmentsUseCase->execute($sale->getId());

        $articles = $this->saleArticleRepository->findBySaleId($sale->getId());
        $serialsByArticle = $this->saleItemSerialRepository->findSerialsBySaleId($sale->getId());
        $articles = array_map(function ($article) use ($serialsByArticle) {
            $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
            return $article;
        }, $articles);

        $saleResource = new SaleResource($sale);
        $saleResource->installments = $installments;

        return response()->json(
            [
                'sale' => $saleResource->resolve(),
                'articles' => SaleArticleResource::collection($articles)->resolve(),
                'estado' => $bloqueado
            ]
        );
    }

    public function showCreditNote($id): JsonResponse
    {
        $saleCreditNoteUseCase = new FindCreditNoteByIdUseCase($this->saleRepository);
        $saleCreditNote = $saleCreditNoteUseCase->execute($id);

        if (!$saleCreditNote) {
            return response()->json(['message' => 'Nota de crédito no encontrada'], 404);
        }

        $articles = $this->saleArticleRepository->findBySaleId($saleCreditNote->getId());
        $serialsUseCase = new FindSerialsInactiveBySaleIdUseCase($this->saleItemSerialRepository);
        $saleUseCase = new FindByDocumentSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($saleCreditNote->getReferenceDocumentTypeId(), $saleCreditNote->getReferenceSerie(), $saleCreditNote->getReferenceCorrelative());
        $serials = $serialsUseCase->execute($sale->getId());

        $articles = array_map(function ($article) use ($serials) {
            $article->serials = $serials[$article->getArticle()->getId()] ?? [];
            return $article;
        }, $articles);

        return response()->json(
            [
                'sale' => (new SaleCreditNoteResource($saleCreditNote))->resolve(),
                'articles' => SaleArticleCreditNoteResource::collection($articles)->resolve(),
            ]
        );
    }

    public function update(UpdateSaleRequest $request, $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
            $sale = $saleUseCase->execute($id);

            $documentReferenceUseCase = new FindByDocumentReferenceUseCase($this->saleRepository);
            $documentReference = $documentReferenceUseCase->execute($sale->getDocumentType()->getId(), $sale->getSerie(), $sale->getDocumentNumber());

            if (!$sale) {
                return response()->json(['message' => 'Venta no encontrada'], 404);
            }

            if ($sale->getIsLocked() == 1) {
                return response()->json(['message' => 'La venta no se puede actualizar por cierre de mes'], 200);
            }

            if ($sale->getSunatStatus() === 'ACEPTADA') {
                return response()->json(['message' => 'La venta no se puede actualizar por ser aceptada por SUNAT'], 200);
            }

            if ($sale->getSaldo() != $sale->getTotal())
            {
                return response()->json(['message' => 'La venta no se puede actualizar porque ya tiene pagos registrados.'], 200);
            }

            if ($documentReference) {
                return response()->json(['message' => 'La venta no se puede actualizar porque ya tiene notas de crédito'], 200);
            }

            $saleDTO = new SaleDTO($request->validated());
            $saleUseCase = new UpdateSaleUseCase($this->saleRepository, $this->companyRepository, $this->branchRepository, $this->userRepository, $this->currencyTypeRepository, $this->documentTypeRepository, $this->customerRepository, $this->paymentTypeRepository, $this->paymentMethodRepository);
            $saleUpdated = $saleUseCase->execute($saleDTO, $sale);

            $installmentUseCase = new FindInstallmentBySaleIdUseCase($this->installmentRepository);
            $installments = $installmentUseCase->execute($saleUpdated->getId());

            if ($installments) {
                $deleteInstallmentUseCase = new DeleteInstallmentUseCase($this->installmentRepository);
                $deleteInstallmentUseCase->execute($saleUpdated->getId());
            }

            if (!empty($request->validated()['installments'])) {
                foreach ($request->validated()['installments'] as $installmentData) {
                    $installmentDTO = new InstallmentDTO([
                        'sale_id' => $sale->getId(),
                        'installment_number' => $installmentData['installment_number'],
                        'amount' => $installmentData['amount'],
                        'due_date' => $installmentData['due_date'],
                    ]);
                    $installmentUseCase = new CreateInstallmentUseCase($this->installmentRepository);
                    $installmentUseCase->execute($installmentDTO);
                }
            }

            $this->saleArticleRepository->deleteBySaleId($saleUpdated->getId());

            $deleteSaleItemSerialBySaleIdUseCase = new DeleteSaleItemSerialBySaleIdUseCase($this->saleItemSerialRepository);
            $deleteSaleItemSerialBySaleIdUseCase->execute($saleUpdated->getId());

            $saleArticles = $this->updateSaleArticles($saleUpdated, $request->validated()['sale_articles']);
            $this->logTransaction($request, $saleUpdated);

            return response()->json(
                [
                    'sale' => (new SaleResource($saleUpdated))->resolve(),
                    'articles' => SaleArticleResource::collection($saleArticles)->resolve()
                ],
                200
            );
        });
    }

    public function updateCreditNote(UpdateSaleCreditNoteRequest $request, $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $creditNoteUseCase = new FindCreditNoteByIdUseCase($this->saleRepository);
            $saleCreditNote = $creditNoteUseCase->execute($id);

            if (!$saleCreditNote) {
                return response()->json(['message' => 'Nota de crédito no encontrada'], 404);
            }

            if ($saleCreditNote->getIsLocked() == 1) {
                return response()->json(['message' => 'La nota de crédito no se puede actualizar por cierre de mes'], 200);
            }

            $saleCreditNoteDTO = new SaleCreditNoteDTO($request->validated());
            $saleCreditNoteUseCase = new UpdateCreditNoteUseCase($this->saleRepository, $this->companyRepository, $this->userRepository, $this->noteReasonRepository);
            $saleCreditNoteUpdated = $saleCreditNoteUseCase->execute($saleCreditNoteDTO, $id);

            $this->saleArticleRepository->deleteBySaleId($saleCreditNoteUpdated->getId());

            $saleArticles = $this->createSaleArticles($saleCreditNoteUpdated, $request->validated()['sale_articles']);
            $this->logTransaction($request, $saleCreditNoteUpdated);

            return response()->json(
                [
                    'sale' => (new SaleCreditNoteResource($saleCreditNoteUpdated))->resolve(),
                    'articles' => SaleArticleCreditNoteResource::collection($saleArticles)->resolve()
                ],
                200
            );
        });
    }

    public function showDocumentSale(Request $request): JsonResponse
    {
        $documentTypeId = $request->query('document_type_id');
        $serie = $request->query('serie');
        $correlative = $request->query('correlative');

        $paddedCorrelative = str_pad($correlative, 8, '0', STR_PAD_LEFT);

        if ($documentTypeId == 9) {
            $dispatchNoteUseCase = new \App\Modules\DispatchNotes\Application\UseCases\FindByDocumentUseCase($this->dispatchNoteRepository);
            $dispatchNote = $dispatchNoteUseCase->execute($serie, $paddedCorrelative);
            if (!$dispatchNote) {
                return response()->json(['message' => 'Nota de despacho no encontrada'], 404);
            }

            $articles = $this->dispatchNoteArticleRepository->findByDispatchNoteId($dispatchNote->getId());
            $serialsByArticle = $this->dispatchArticleSerialRepository->findSerialsByTransferOrderId($dispatchNote->getId());
            $articles = array_map(function ($article) use ($serialsByArticle) {
                $article->serials = $serialsByArticle[$article->getArticleId()] ?? [];
                return $article;
            }, $articles);

            return response()->json([
                'dispatch_note' => (new DispatchNoteResource($dispatchNote))->resolve(),
                'articles' => DispatchArticleResource::collection($articles)->resolve()
            ]);
        } else {
            $saleUseCase = new FindByDocumentSaleUseCase($this->saleRepository);
            $sale = $saleUseCase->execute($documentTypeId, $serie, $paddedCorrelative);
            if (!$sale) {
                return response()->json(['message' => 'Venta no encontrada'], 404);
            }

            $articles = $this->saleArticleRepository->findBySaleId($sale->getId());
            $serialsByArticle = $this->saleItemSerialRepository->findSerialsBySaleId($sale->getId());
            $articles = array_map(function ($article) use ($serialsByArticle) {
                $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
                return $article;
            }, $articles);

            return response()->json([
                'sale' => (new SaleResource($sale))->resolve(),
                'articles' => SaleArticleResource::collection($articles)->resolve()
            ]);
        }
    }

    public function findSaleByDocumentForDebitNote(Request $request): JsonResponse
    {
        $documentTypeId = $request->query('document_type_id');
        $serie = $request->query('serie');
        $correlative = $request->query('correlative');

        $saleUseCase = new FindByDocumentSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($documentTypeId, $serie, $correlative);
        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        return response()->json([
            'sale' => (new SaleResource($sale))->resolve()
        ]);
    }

    public function findAllPendingSalesByCustomerId(Request $request): JsonResponse
    {
        $customerId = $request->query('customer_id');

        $saleUseCase = new FindAllPendingSalesByCustomerIdUseCase($this->saleRepository);
        $sales = $saleUseCase->execute($customerId);
        if (!$sales) {
            return response()->json(['message' => 'Este cliente no tiene ventas pendientes.'], 200);
        }

        return response()->json([
            'sales' => SaleResource::collection($sales)->resolve()
        ]);
    }

    public function findAllDocumentsByCustomerId(Request $request): JsonResponse
    {
        $customerId = $request->query('customer_id');
        $payment_status = $request->query('payment_status') !== null ? (int) $request->query('payment_status') : null;
        $user_sale_id = $request->query('user_sale_id');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $document_type_id = $request->query('document_type_id');

        $saleUseCase = new FindAllDocumentsByCustomerIdUseCase($this->saleRepository);
        $paginatedSales = $saleUseCase->execute($customerId, $payment_status, $user_sale_id, $start_date, $end_date, $document_type_id);

        return response()->json([
            'totals' => $paginatedSales->totals,
            'data' => DocumentByCustomerResource::collection($paginatedSales)->resolve(),
            'current_page' => $paginatedSales->currentPage(),
            'per_page' => $paginatedSales->perPage(),
            'total' => $paginatedSales->total(),
            'last_page' => $paginatedSales->lastPage(),
            'next_page_url' => $paginatedSales->nextPageUrl(),
            'prev_page_url' => $paginatedSales->previousPageUrl(),
            'first_page_url' => $paginatedSales->url(1),
            'last_page_url' => $paginatedSales->url($paginatedSales->lastPage())
        ]);
    }

    private function createSaleArticles($sale, array $articlesData): array
    {
        $createSaleArticleUseCase = new CreateSaleArticleUseCase($this->saleArticleRepository, $this->articleRepository);

        // Calcular primero el subtotal_costo_neto total de todos los artículos
        $subtotal_costo_neto = 0;
        foreach ($articlesData as $article) {
            $subtotal_costo_neto += $article['purchase_price'] ?? 0 * $article['quantity'];
        }

        // Procesar los artículos
        $saleArticles = [];
        foreach ($articlesData as $article) {
            $saleArticleDTO = new SaleArticleDTO([
                'sale_id' => $sale->getId(),
                'article_id' => $article['article_id'],
                'description' => $article['description'],
                'quantity' => $article['quantity'],
                'unit_price' => $article['unit_price'],
                'public_price' => $article['public_price'],
                'subtotal' => $article['subtotal'],
                'purchase_price' => $article['purchase_price'] ?? 0,
                'costo_neto' => $article['purchase_price'] ?? 0 * $article['quantity']
            ]);

            $saleArticle = $createSaleArticleUseCase->execute($saleArticleDTO, $subtotal_costo_neto);

            // Array para almacenar los seriales
            $serials = [];

            if (!empty($article['serials'])) {
                foreach ($article['serials'] as $serial) {
                    $itemSerialDTO = new SaleItemSerialDTO([
                        'sale' => $sale,
                        'article' => $saleArticle,
                        'serial' => $serial,
                    ]);
                    $itemSerialUseCase = new CreateSaleItemSerialUseCase($this->saleItemSerialRepository);
                    $itemSerial = $itemSerialUseCase->execute($itemSerialDTO);
                    $serials[] = $itemSerial;
                }
            }

            // Agregar los seriales al objeto saleArticle
            $saleArticle->serials = $serials;
            $saleArticles[] = $saleArticle;
        }

        return $saleArticles;
    }

    private function updateSaleArticles($sale, array $articlesData): array
    {
        $createSaleArticleUseCase = new CreateSaleArticleUseCase($this->saleArticleRepository, $this->articleRepository);

        // Calcular primero el subtotal_costo_neto total de todos los artículos
        $subtotal_costo_neto = 0;
        foreach ($articlesData as $article) {
            $subtotal_costo_neto += $article['purchase_price'] * $article['quantity'];
        }

        // Procesar los artículos
        $saleArticles = [];
        foreach ($articlesData as $article) {
            $saleArticleDTO = new SaleArticleDTO([
                'sale_id' => $sale->getId(),
                'article_id' => $article['article_id'],
                'description' => $article['description'],
                'quantity' => $article['quantity'],
                'unit_price' => $article['unit_price'],
                'public_price' => $article['public_price'],
                'subtotal' => $article['subtotal'],
                'purchase_price' => $article['purchase_price'],
                'costo_neto' => $article['purchase_price'] * $article['quantity'],
                'warranty' => $article['warranty']
            ]);

            $saleArticle = $createSaleArticleUseCase->execute($saleArticleDTO, $subtotal_costo_neto);

            // Array para almacenar los seriales
            $serials = [];
            if (!empty($article['serials'])) {
                foreach ($article['serials'] as $serial) {
                    $itemSerialDTO = new SaleItemSerialDTO([
                        'sale' => $sale,
                        'article' => $saleArticle,
                        'serial' => $serial,
                    ]);
                    $itemSerialUseCase = new CreateSaleItemSerialUseCase($this->saleItemSerialRepository);
                    $itemSerial = $itemSerialUseCase->execute($itemSerialDTO);
                    $serials[] = $itemSerial;
                }
            }

            // Agregar los seriales al objeto saleArticle
            $saleArticle->serials = $serials;
            $saleArticles[] = $saleArticle;
        }

        return $saleArticles;
    }

    private function logTransaction($request, $sale): void
    {
        $transactionLogs = new CreateTransactionLogUseCase(
            $this->transactionLogRepository,
            $this->userRepository,
            $this->companyRepository,
            $this->documentTypeRepository,
            $this->branchRepository
        );

        $documentTypeId = $sale->getDocumentType()->getId();

        $description = match ($documentTypeId) {
            7 => 'Nota de crédito',
            8 => 'Nota de débito',
            1 => 'Venta',
            3 => 'Venta',
            default => 'Proforma'
        };

        $transactionDTO = new TransactionLogDTO([
            'user_id' => request()->get('user_id'),
            'role_name' => request()->get('role'),
            'description_log' => $description,
            'action' => $request->method(),
            'company_id' => $sale->getCompany()->getId(),
            'branch_id' => $sale->getBranch()->getId(),
            'document_type_id' => $sale->getDocumentType()->getId(),
            'serie' => $sale->getSerie(),
            'correlative' => $sale->getDocumentNumber(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $transactionLogs->execute($transactionDTO);
    }

    public function indexProformas(Request $request): JsonResponse
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status') !== null ? $request->query('status') : null;
        $description = $request->query('description');

        $saleUseCase = new FindAllProformasUseCase($this->saleRepository);
        $sales = $saleUseCase->execute($startDate, $endDate, $status, $description);

        $result = [];
        foreach ($sales as $sale) {
            $articles = $this->saleArticleRepository->findBySaleId($sale->getId());
            $serialsByArticle = $this->saleItemSerialRepository->findSerialsBySaleId($sale->getId());

            $articlesWithSerials = array_map(function ($article) use ($serialsByArticle) {
                $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
                return $article;
            }, $articles);

            $result[] = [
                'sale' => (new SaleResource($sale))->resolve(),
                'articles' => SaleArticleResource::collection($articlesWithSerials)->resolve(),
            ];
        }

        return new JsonResponse([
            'data' => $result,
            'current_page' => $sales->currentPage(),
            'per_page' => $sales->perPage(),
            'total' => $sales->total(),
            'last_page' => $sales->lastPage(),
            'next_page_url' => $sales->nextPageUrl(),
            'prev_page_url' => $sales->previousPageUrl(),
            'first_page_url' => $sales->url(1),
            'last_page_url' => $sales->url($sales->lastPage()),
        ]);
    }

    public function getUpdatedQuantities(Request $request, FindSaleWithUpdatedQuantitiesUseCase $useCase): JsonResponse
    {
        $request->validate([
            'reference_document_type_id' => 'required|integer',
            'reference_serie' => 'required|string',
            'reference_correlative' => 'required|string',
        ]);

        $paddedCorrelative = str_pad($request->query('reference_correlative'), 8, '0', STR_PAD_LEFT);

        try {

            $saleUseCase = new FindByDocumentSaleUseCase($this->saleRepository);
            $sale = $saleUseCase->execute(
                (int) $request->query('reference_document_type_id'),
                $request->query('reference_serie'),
                $paddedCorrelative
            );

            if (!$sale) {
                return response()->json(['message' => 'Venta no encontrada'], 404);
            }

            $result = $useCase->execute(
                (int) $request->query('reference_document_type_id'),
                $request->query('reference_serie'),
                $paddedCorrelative
            );

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Venta no encontrada'
                ], 404);
            }

            $saleData = [
                'sale' => (new SaleResource($this->saleRepository->findById($result['sale']->id)))->resolve(),
                'articles' => $result['articles'],
                'has_credit_notes' => $result['has_credit_notes'],
            ];

            return response()->json($saleData, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las cantidades actualizadas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexCreditNotesByCustomer(Request $request): JsonResponse
    {
        $customerId = $request->query('customer_id');

        $creditNoteUseCase = new FindAllNoteCreditsByCustomerUseCase($this->saleRepository);
        $creditNotes = $creditNoteUseCase->execute($customerId);

        return response()->json(array_values($creditNotes), 200);
    }

    public function generatePdf(int $id): JsonResponse
    {
        $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($id);

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        $saleArticles = $this->saleArticleRepository->findBySaleId($sale->getId());
        $serialsByArticle = $this->saleItemSerialRepository->findSerialsBySaleId($sale->getId());

        $saleArticles = array_map(function ($article) use ($serialsByArticle) {
            $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
            return $article;
        }, $saleArticles);

        $transactionLogUseCase = new FindByDocumentUseCase($this->transactionLogRepository);
        $transactionLog = $transactionLogUseCase->execute($sale->getSerie(), $sale->getDocumentNumber());

        // Generate QR code data (SUNAT format)
        $qrData = sprintf(
            "%s|%s|%s|%s|%s|%s|%s|%s|%s",
            $sale->getCompany()->getRuc(),
            str_pad($sale->getDocumentType()->getCodSunat(), 2, '0', STR_PAD_LEFT),
            $sale->getSerie(),
            $sale->getDocumentNumber(),
            number_format($sale->getIgv(), 2, '.', ''),
            number_format($sale->getTotal(), 2, '.', ''),
            $sale->getDate(),
            $sale->getCustomer()->getCustomerDocumentType()->getId(),
            $sale->getCustomer()->getDocumentNumber()
        );

        // Generate QR code as base64 image using GD backend
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(150, 1),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrCode = base64_encode($writer->writeString($qrData));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sale', [
            'sale' => $sale,
            'saleArticles' => $saleArticles,
            'qrCode' => $qrCode,
            'transactionLog' => $transactionLog
        ]);

        $documentTypeName = str_replace(' ', '_', $sale->getDocumentType()->getDescription());
        $fileName = $documentTypeName . '_' . $sale->getSerie() . '-' . $sale->getDocumentNumber() . '.pdf';

        $path = 'pdf/' . $fileName;
        $content = $pdf->output();
        Storage::disk('public')->put($path, $content);

        return response()->json([
            'url' => asset('storage/' . $path),
            'fileName' => $fileName,
            'pdf_base64' => base64_encode($content)
        ]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $result = DB::select('CALL sp_comunicacion_anulacion_baja(?)', [$id]);

        $estado = $result[0]->estado;
        $msg = $result[0]->msg;

        if ($estado == 0) {
            return response()->json([
                'message' => $msg,
                'status' => false
            ], 200);
        }
            
        $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($id);
        
        if ($sale->getSaldo() != $sale->getTotal())
        {
            return response()->json(['message' => 'La venta no se puede anular porque ya tiene pagos registrados.', 'status' => false], 200);
        }
        
        $documentReferenceUseCase = new FindByDocumentReferenceUseCase($this->saleRepository);
        $documentReference = $documentReferenceUseCase->execute($sale->getDocumentType()->getId(), $sale->getSerie(), $sale->getDocumentNumber());

        if ($documentReference) {
            return response()->json(['message' => 'La venta no se puede anular porque ya tiene notas de crédito', 'status' => false], 200);
        }
        
        $serialsByArticle = $this->saleItemSerialRepository->findSerialsBySaleId($sale->getId());
        $serials = array_merge(...array_values($serialsByArticle));
        
        // Actualizando el estado de las series para que estén habilitadas nuevamente para la venta.
        $entryItemSerialUseCase = new UpdateStatusBySerialUseCase($this->entryItemSerialRepository);
        foreach ($serials as $serial) {
            $entryItemSerialUseCase->execute($serial, 1);
        }
        
        $statusUseCase = new UpdateStatusSalesUseCase($this->saleRepository);
        $statusUseCase->execute($id, 0);
        
        return response()->json([
            'message' => 'Documento anulado correctamente',
            'status' => true
        ], 200);
        
        /*if ($sale->getDocumentType()->getId() == 1) {
            $response = $this->salesSunatService->saleInvoiceAnulacion($sale);
        }

        $saleEloquent = EloquentSale::find($id);
        $saleEloquent->update([
            'estado_sunat' => 'ANULADA',
            'fecha_baja_sunat' => $response['fecha_respuesta'],
            'hora_baja_sunat' => $response['hora_respuesta']
        ]);*/
    }

}

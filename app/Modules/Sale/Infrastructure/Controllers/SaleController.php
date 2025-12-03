<?php

namespace App\Modules\Sale\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Collections\Infrastructure\Models\EloquentCollection;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\Installment\Application\DTOs\InstallmentDTO;
use App\Modules\Installment\Application\UseCases\CreateInstallmentUseCase;
use App\Modules\Installment\Application\UseCases\DeleteInstallmentUseCase;
use App\Modules\Installment\Application\UseCases\FindInstallmentBySaleIdUseCase;
use App\Modules\Installment\Domain\Interface\InstallmentRepositoryInterface;
use App\Modules\NoteReason\Domain\Interfaces\NoteReasonRepositoryInterface;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\PurchaseItemSerials\Application\UseCases\FindBySerialUseCase;
use App\Modules\Sale\Application\DTOs\SaleCreditNoteDTO;
use App\Modules\Sale\Application\DTOs\SaleDTO;
use App\Modules\Sale\Application\UseCases\CreateSaleCreditNoteUseCase;
use App\Modules\Sale\Application\UseCases\CreateSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindAllDocumentsByCustomerIdUseCase;
use App\Modules\Sale\Application\UseCases\FindAllNoteCreditsByCustomerUseCase;
use App\Modules\Sale\Application\UseCases\FindAllPendingSalesByCustomerIdUseCase;
use App\Modules\Sale\Application\UseCases\FindAllProformasUseCase;
use App\Modules\Sale\Application\UseCases\FindAllSalesByCustomerIdUseCase;
use App\Modules\Sale\Application\UseCases\FindAllSalesUseCase;
use App\Modules\Sale\Application\UseCases\FindSaleWithUpdatedQuantitiesUseCase;
use App\Modules\Sale\Application\UseCases\FindByDocumentSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindByIdSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindCreditNoteByIdUseCase;
use App\Modules\Sale\Application\UseCases\UpdateCreditNoteUseCase;
use App\Modules\Sale\Application\UseCases\UpdateSaleUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\Sale\Infrastructure\Requests\StoreSaleCreditNoteRequest;
use App\Modules\Sale\Infrastructure\Requests\StoreSaleRequest;
use App\Modules\Sale\Infrastructure\Requests\UpdateSaleCreditNoteRequest;
use App\Modules\Sale\Infrastructure\Requests\UpdateSaleRequest;
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
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Application\UseCases\FindByDocumentUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        private readonly InstallmentRepositoryInterface $installmentRepository
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $companyId = request()->get('company_id');
        $saleUseCase = new FindAllSalesUseCase($this->saleRepository);
        $sales = $saleUseCase->execute($companyId);

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

        return response()->json($result, 200);
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $saleDTO = new SaleDTO($request->validated());
            $saleUseCase = new CreateSaleUseCase($this->saleRepository, $this->companyRepository, $this->branchRepository, $this->userRepository, $this->currencyTypeRepository, $this->documentTypeRepository, $this->customerRepository, $this->paymentTypeRepository, $this->documentNumberGeneratorService);
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

            if (!$sale) {
                return response()->json(['message' => 'Venta no encontrada'], 404);
            }

            if ($sale->getIsLocked() == 1) {
                return response()->json(['message' => 'La venta no se puede actualizar por cierre de mes'], 200);
            }

            $saleDTO = new SaleDTO($request->validated());
            $saleUseCase = new UpdateSaleUseCase($this->saleRepository, $this->companyRepository, $this->branchRepository, $this->userRepository, $this->currencyTypeRepository, $this->documentTypeRepository, $this->customerRepository, $this->paymentTypeRepository);
            $saleUpdated = $saleUseCase->execute($saleDTO, $sale);

            $installmentUseCase = new FindInstallmentBySaleIdUseCase($this->installmentRepository);
            $installments = $installmentUseCase->execute($saleUpdated->getId());

            if ($installments) {
                $deleteInstallmentUseCase = new DeleteInstallmentUseCase($this->installmentRepository);
                $deleteInstallmentUseCase->execute($saleUpdated->getId());

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
            'data' => SaleResource::collection($paginatedSales)->resolve(),
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
        $subtotal_costo_neto = 0;
        return array_map(function ($article) use ($sale, $createSaleArticleUseCase, &$subtotal_costo_neto) {
            $saleArticleDTO = new SaleArticleDTO([
                'sale_id' => $sale->getId(),
                'article_id' => $article['article_id'],
                'description' => $article['description'],
                'quantity' => $article['quantity'],
                'unit_price' => $article['unit_price'],
                'public_price' => $article['public_price'],
                'subtotal' => $article['subtotal'],
                'purchase_price' => $article['purchase_price'],
                'costo_neto' => $article['purchase_price'] * $article['quantity']
            ]);
            $subtotal_costo_neto += $saleArticleDTO->costo_neto;
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

            return $saleArticle;
        }, $articlesData);
    }

    private function updateSaleArticles($sale, array $articlesData): array
    {
        $createSaleArticleUseCase = new CreateSaleArticleUseCase($this->saleArticleRepository, $this->articleRepository);
        $subtotal_costo_neto = 0;

        return array_map(function ($article) use ($sale, $createSaleArticleUseCase, $subtotal_costo_neto) {
            $saleArticleDTO = new SaleArticleDTO([
                'sale_id' => $sale->getId(),
                'article_id' => $article['article_id'],
                'description' => $article['description'],
                'quantity' => $article['quantity'],
                'unit_price' => $article['unit_price'],
                'public_price' => $article['public_price'],
                'subtotal' => $article['subtotal'],
                'purchase_price' => $article['purchase_price'],
                'costo_neto' => $article['purchase_price'] * $article['quantity']
            ]);
            $subtotal_costo_neto += $saleArticleDTO->costo_neto;

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

            return $saleArticle;
        }, $articlesData);
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

    public function indexProformas(): array
    {
        $saleUseCase = new FindAllProformasUseCase($this->saleRepository);
        $sales = $saleUseCase->execute();

        return SaleResource::collection($sales)->resolve();
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

    public function generatePdf(int $id)
    {
        $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($id);

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        $saleArticles = $this->saleArticleRepository->findBySaleId($sale->getId());

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
            $sale->getCustomer()->getCustomerDocumentTypeId(),
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

        $documentTypeName = $sale->getDocumentType()->getDescription();
        return $pdf->stream($documentTypeName . '_' . $sale->getSerie() . '-' . $sale->getDocumentNumber() . '.pdf');
    }

}

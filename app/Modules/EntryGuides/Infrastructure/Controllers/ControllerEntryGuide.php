<?php

namespace App\Modules\EntryGuides\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DetEntryguidePurchaseorder\application\DTOS\DetEntryguidePurchaseorderDTO;
use App\Modules\DetEntryguidePurchaseOrder\application\UseCases\CreateDetEntryguidePurchaseOrderUseCase;
use App\Modules\DetEntryguidePurchaseOrder\Domain\Interface\DetEntryguidePurchaseOrderRepositoryInterface;
use App\Modules\DetEntryguidePurchaseOrder\Infrastrucutre\Resource\DetEntryguidePurchaseOrderResource;
use App\Modules\DocumentEntryGuide\application\DTOS\DocumentEntryGuideDTO;
use App\Modules\DocumentEntryGuide\application\UseCases\CreateDocumentEntryGuide;
use App\Modules\DocumentEntryGuide\Domain\Interface\DocumentEntryGuideRepositoryInterface;
use App\Modules\DocumentEntryGuide\Infrastructure\Resource\DocumentEntryGuideResource;
use App\Modules\ShoppingIncomeGuide\Domain\Interface\ShoppingIncomeGuideRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\EntryGuideArticle\Application\DTOS\EntryGuideArticleDTO;
use App\Modules\EntryGuideArticle\Application\UseCases\CreateEntryGuideArticle;
use App\Modules\EntryGuides\Application\DTOS\EntryGuideDTO;
use App\Modules\EntryGuides\Application\UseCases\CreateEntryGuideUseCase;
use App\Modules\EntryGuides\Application\UseCases\FindAllEntryGuideUseCase;
use App\Modules\EntryGuides\Application\UseCases\FindByIdEntryGuideUseCase;
use App\Modules\EntryGuides\Application\UseCases\UpdateEntryGuideUseCase;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryGuides\Infrastructure\Request\EntryGuideRequest;
use App\Modules\EntryGuides\Infrastructure\Request\UpdateGuideRequest;
use App\Modules\EntryGuides\Infrastructure\Resource\EntryGuideResource;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\EntryGuideArticle\Domain\Interface\EntryGuideArticleRepositoryInterface;
use App\Modules\EntryGuideArticle\Infrastructure\Resource\EntryGuideArticleResource;
use App\Modules\EntryGuides\Application\UseCases\GeneratePDF;
use App\Modules\EntryGuides\Application\UseCases\UpdateStatusUseCase;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuidePDF;
use App\Modules\EntryGuides\Infrastructure\Persistence\ExcelEntryGuide;
use App\Modules\EntryItemSerial\Application\DTOS\EntryItemSerialDTO;
use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;
use App\Modules\EntryItemSerial\Application\UseCases\CreateEntryItemSerialUseCase;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Modules\Purchases\Application\UseCases\CreatePurchaseUseCase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\Purchases\Application\DTOS\PurchaseDTO;
use App\Modules\Purchases\Application\UseCases\UpdatePurchaseUseCase;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;
use Maatwebsite\Excel\Facades\Excel;


class ControllerEntryGuide extends Controller
{
    public function __construct(
        private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface,
        private readonly CompanyRepositoryInterface $companyRepositoryInterface,
        private readonly BranchRepositoryInterface $branchRepositoryInterface,
        private readonly CustomerRepositoryInterface $customerRepositoryInterface,
        private readonly EntryGuideArticleRepositoryInterface $entryGuideArticleRepositoryInterface,
        private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepositoryInterface,
        private readonly IngressReasonRepositoryInterface $ingressReasonRepositoryInterface,
        private readonly ArticleRepositoryInterface $articleRepositoryInterface,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly TransactionLogRepositoryInterface $transactionLogRepositoryInterface,
        private readonly UserRepositoryInterface $userRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly DocumentEntryGuideRepositoryInterface $documentEntryGuideRepositoryInterface,
        private readonly DetEntryguidePurchaseOrderRepositoryInterface $detEntryguidePurchaseOrderRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepositoryInterface,
        private readonly PurchaseRepositoryInterface $purchaseRepositoryInterface,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepositoryInterface,
        private readonly SerieRepositoryInterface $serieRepositoryInterface,
        private readonly ExchangeRateRepositoryInterface $exchangeRateRepositoryInterface,
        private readonly ShoppingIncomeGuideRepositoryInterface $shoppingIncomeGuideRepositoryInterface,
    ) {}

    public function index(Request $request): JsonResponse
    {

        $description = $request->query('description');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;

        $reference_document_id = $request->query('reference_document_id');
        $reference_serie = $request->query('reference_serie');
        $reference_correlative = $request->query('reference_correlative');

        $supplier_id = $request->query('supplier_id');


        $entryGuideUseCase = new FindAllEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuides = $entryGuideUseCase->execute(
            $description,
            $status,
            $reference_document_id,
            $reference_serie,
            $reference_correlative,
            $supplier_id,
        );

        $result = [];

        foreach ($entryGuides as $entryGuide) {
            $articles = $this->entryGuideArticleRepositoryInterface->findById($entryGuide->getId());
            $serialsByArticle = $this->entryItemSerialRepositoryInterface->findSerialsByEntryGuideId($entryGuide->getId());
            $documentEntryGuide = $this->documentEntryGuideRepositoryInterface->findByIdObj($entryGuide->getId());
            $detEntryguidePurchaseOrder = $this->detEntryguidePurchaseOrderRepository->findByIdEntryGuide($entryGuide->getId());

            $articlesWithSerials = array_map(function ($article) use ($serialsByArticle, $documentEntryGuide, $detEntryguidePurchaseOrder) {
                $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
                $article->document_entry_guide = $documentEntryGuide;
                $article->order_purchase_id = $detEntryguidePurchaseOrder;
                return $article;
            }, $articles);



            $response = (new EntryGuideResource($entryGuide))->resolve();
            $response['articles'] = EntryGuideArticleResource::collection($articlesWithSerials)->resolve();
            $response['document_entry_guide'] = (new DocumentEntryGuideResource($documentEntryGuide))->resolve();
            $response['order_purchase_id'] = DetEntryguidePurchaseOrderResource::collection($detEntryguidePurchaseOrder)->resolve();
            $response['process_status'] = $this->calculateProcessStatus($articlesWithSerials, $documentEntryGuide);
            $result[] = $response;
        }

        return new JsonResponse([
            'data' => $result,
            'current_page' => $entryGuides->currentPage(),
            'per_page' => $entryGuides->perPage(),
            'total' => $entryGuides->total(),
            'last_page' => $entryGuides->lastPage(),
            'next_page_url' => $entryGuides->nextPageUrl(),
            'prev_page_url' => $entryGuides->previousPageUrl(),
            'first_page_url' => $entryGuides->url(1),
            'last_page_url' => $entryGuides->url($entryGuides->lastPage()),
        ]);
    }

    public function indexC(Request $request): JsonResponse
    {

        $serie = $request->query('serie');
        $correlativo = $request->query('correlativo');

        $entryGuides = $this->entryGuideRepositoryInterface->findByCorrelative($correlativo);

        $result = [];

        foreach ($entryGuides as $entryGuide) {
            $articles = $this->entryGuideArticleRepositoryInterface->findById($entryGuide->getId());
            $serialsByArticle = $this->entryItemSerialRepositoryInterface->findSerialsByEntryGuideId($entryGuide->getId());
            $detEntryguidePurchaseOrder = $this->detEntryguidePurchaseOrderRepository->findByIdEntryGuide($entryGuide->getId());

            $articlesWithSerials = array_map(function ($article) use ($serialsByArticle, $detEntryguidePurchaseOrder) {
                $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
                $article->order_purchase_id = $detEntryguidePurchaseOrder;
                return $article;
            }, $articles);

            $docEntryGuide = $this->documentEntryGuideRepositoryInterface->findByIdObj($entryGuide->getId());

            $response = (new EntryGuideResource($entryGuide))->resolve();
            $response['articles'] = EntryGuideArticleResource::collection($articlesWithSerials)->resolve();
            $response['order_purchase_id'] = DetEntryguidePurchaseOrderResource::collection($detEntryguidePurchaseOrder)->resolve();
            $response['process_status'] = $this->calculateProcessStatus($articlesWithSerials, $docEntryGuide);
            $result[] = $response;
        }

        return response()->json($result, 200);
    }

    public function show($id): JsonResponse
    {
        $entryGuideUseCase = new FindByIdEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute($id);

        if (!$entryGuide) {
            return response()->json(['message' => 'Guía de ingreso no encontrada'], 404);
        }

        $entryArticles = $this->entryGuideArticleRepositoryInterface->findById($entryGuide->getId());
        $serialsByArticle = $this->entryItemSerialRepositoryInterface->findSerialsByEntryGuideId($entryGuide->getId());
        $documentEntryGuide = $this->documentEntryGuideRepositoryInterface->findByIdObj($entryGuide->getId());
        $detEntryguidePurchaseOrder = $this->detEntryguidePurchaseOrderRepository->findByIdEntryGuide($entryGuide->getId());

        $entryArticles = array_map(function ($article) use ($serialsByArticle, $documentEntryGuide, $detEntryguidePurchaseOrder) {
            $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
            $article->document_entry_guide = $documentEntryGuide;
            $article->order_purchase_id = $detEntryguidePurchaseOrder;
            return $article;
        }, $entryArticles);

        $response = (new EntryGuideResource($entryGuide))->resolve();
        $response['articles'] = EntryGuideArticleResource::collection($entryArticles)->resolve();
        $response['document_entry_guide'] = (new DocumentEntryGuideResource($documentEntryGuide))->resolve();
        $response['order_purchase_id'] = DetEntryguidePurchaseOrderResource::collection($detEntryguidePurchaseOrder)->resolve();
        $response['process_status'] = $this->calculateProcessStatus($entryArticles, $documentEntryGuide);

        return response()->json($response, 200);
    }

    public function store(EntryGuideRequest $request): JsonResponse
    {

        return DB::transaction(function () use ($request) {

            $data = $request->validated();
            $data['reference_document_id'] = $data['reference_document_id'] ?? 0;
            $data['document_entry_guide']['reference_document_id'] = $data['document_entry_guide']['reference_document_id'] ?? 0;




            $entryGuideDTO = new EntryGuideDTO($data);
            $entryGuideUseCase = new CreateEntryGuideUseCase(
                $this->entryGuideRepositoryInterface,
                $this->companyRepositoryInterface,
                $this->branchRepositoryInterface,
                $this->customerRepositoryInterface,
                $this->ingressReasonRepositoryInterface,
                $this->documentNumberGeneratorService,
                $this->currencyTypeRepositoryInterface,
            );
            $entryGuide = $entryGuideUseCase->execute($entryGuideDTO);

            $isFactura = (isset($data['document_entry_guide']['reference_document_id']) && $data['document_entry_guide']['reference_document_id'] == 1);
            $iscredito = (isset($data['document_entry_guide']['reference_document_id']) && $data['document_entry_guide']['reference_document_id'] == 7);
            $isdebito = (isset($data['document_entry_guide']['reference_document_id']) && $data['document_entry_guide']['reference_document_id'] == 8);

            $entryGuideArticle = $this->createEntryGuideArticles($entryGuide, $data['entry_guide_articles'], ($isFactura || $iscredito || $isdebito));
            $documentEntryGuide = $this->updateDocumentEntryGuide($entryGuide, $data['document_entry_guide']);

            if ($isFactura || $iscredito || $isdebito) {
                $purchase = $this->createPurchaseFromEntryGuide($entryGuide, $data);

                $transactionLogs = new CreateTransactionLogUseCase(
                    $this->transactionLogRepositoryInterface,
                    $this->userRepository,
                    $this->companyRepositoryInterface,
                    $this->documentTypeRepository,
                    $this->branchRepositoryInterface
                );

                $transactionDTO = new TransactionLogDTO([
                    'user_id' => request()->get('user_id'),
                    'role_name' => request()->get('role'),
                    'description_log' => 'Caja Chica',
                    'observations' => $observations ?? ($request->method() == 'POST' ? 'Registro de documento.' : 'Actualización de documento.'),
                    'action' => $request->method(),
                    'company_id' => $purchase->getCompanyId(),
                    'branch_id' => $purchase->getBranch()->getId(),
                    'document_type_id' => $purchase->getTypeDocumentId()->getId(),
                    'serie' => $purchase->getSerie(),
                    'correlative' => $purchase->getCorrelative(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $transactionLogs->execute($transactionDTO);
            }

            $detEntryguidePurchaseOrder = $this->createDetEntryguidePurchaseOrder($entryGuide, $request->validated()['order_purchase_id'] ?? []);


            $this->logTransaction($request, $entryGuide);

            $response = (new EntryGuideResource($entryGuide))->resolve();
            $response['articles'] = EntryGuideArticleResource::collection($entryGuideArticle)->resolve();
            $response['document_entry_guide'] = (new DocumentEntryGuideResource($documentEntryGuide))->resolve();
            $response['order_purchase_id'] = DetEntryguidePurchaseOrderResource::collection($detEntryguidePurchaseOrder)->resolve();
            $response['process_status'] = $this->calculateProcessStatus($entryGuideArticle, $documentEntryGuide);


            return response()->json($response, 201);
        });
    }

    public function update(UpdateGuideRequest $request, $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $entryGuideUseCase = new FindByIdEntryGuideUseCase($this->entryGuideRepositoryInterface);
            $entryGuide = $entryGuideUseCase->execute($id);

            if (!$entryGuide) {
                return response()->json(['message' => 'Guia de ingreso no encontrada'], 404);
            }

            $entryGuideDTO = new EntryGuideDTO($request->validated());
            $entryGuideUseCase = new UpdateEntryGuideUseCase(
                $this->entryGuideRepositoryInterface,
                $this->companyRepositoryInterface,
                $this->branchRepositoryInterface,
                $this->customerRepositoryInterface,
                $this->ingressReasonRepositoryInterface,
                $this->currencyTypeRepositoryInterface,
            );

            $entryGuide = $entryGuideUseCase->execute($entryGuideDTO, $id);

            $this->entryItemSerialRepositoryInterface->deleteByIdEntryItemSerial($entryGuide->getId());
            $this->entryGuideArticleRepositoryInterface->deleteByEntryGuideId($entryGuide->getId());
            $this->documentEntryGuideRepositoryInterface->deleteByEntryGuideId($entryGuide->getId());
            $this->detEntryguidePurchaseOrderRepository->deleteByEntryGuideId($entryGuide->getId());


            $isFactura = (isset($request->validated()['document_entry_guide']['reference_document_id']) && $request->validated()['document_entry_guide']['reference_document_id'] == 1);
            $iscredito = (isset($request->validated()['document_entry_guide']['reference_document_id']) && $request->validated()['document_entry_guide']['reference_document_id'] == 7);
            $isdebito = (isset($request->validated()['document_entry_guide']['reference_document_id']) && $request->validated()['document_entry_guide']['reference_document_id'] == 8);

            $entryGuideArticle = $this->createEntryGuideArticles($entryGuide, $request->validated()['entry_guide_articles'], ($isFactura || $iscredito || $isdebito));
            $detEntryguidePurchaseOrder =  $this->createDetEntryguidePurchaseOrder($entryGuide, $request->validated()['order_purchase_id'] ?? []);
            $documentEntryGuide = $this->updateDocumentEntryGuide($entryGuide, $request->validated()['document_entry_guide']);

            $this->syncPurchaseFromEntryGuide($entryGuide, array_merge($request->validated(), ['id' => $id]));

            $this->logTransaction($request, $entryGuide);

            $response = (new EntryGuideResource($entryGuide))->resolve();
            $response['articles'] = EntryGuideArticleResource::collection($entryGuideArticle)->resolve();
            $response['document_entry_guide'] = (new DocumentEntryGuideResource($documentEntryGuide))->resolve();
            $response['order_purchase_id'] = DetEntryguidePurchaseOrderResource::collection($detEntryguidePurchaseOrder)->resolve();
            $response['process_status'] = $this->calculateProcessStatus($entryGuideArticle, $documentEntryGuide);

            return response()->json($response, 200);
        });
    }
    private function createEntryGuideArticles($entryGuide, array $articlesData, bool $isFactura = false): array
    {

        $createEntryGuideArticleUseCase = new CreateEntryGuideArticle($this->entryGuideArticleRepositoryInterface, $this->articleRepositoryInterface);
        return array_map(function ($q) use ($entryGuide, $createEntryGuideArticleUseCase, $isFactura) {
            $entryGuideArticleDTO = new EntryGuideArticleDTO([
                'entry_guide_id' => $entryGuide->getId(),
                'article_id' => $q['article_id'],
                'description' => $q['description'],
                'quantity' => $q['quantity'],
                'saldo' => $isFactura ? 0 : ($q['saldo'] ?? $q['quantity']),
                'subtotal' => $q['subtotal'] ?? 0,
                'total' => $q['total'] ?? 0,
                'precio_costo' => $q['precio_costo'] ?? 0,
                'descuento' => $q['descuento'] ?? 0,
            ]);

            $findbyidobt = $this->entryGuideArticleRepositoryInterface->findByIdObj($entryGuide->getId(), $q['article_id']);

            //  dd($findbyidobt);

            $guideArticle = $createEntryGuideArticleUseCase->execute($entryGuideArticleDTO);

            // Array para almacenar los seriales
            $serials = [];

            if (!empty($q['serials'])) {
                if ($entryGuide->getIngressReason()->getId() == 6) {
                    $this->entryItemSerialRepositoryInterface->deleteByIdEntryItemSerial($entryGuide->getId());
                }

                $itemSerialUseCase = new CreateEntryItemSerialUseCase($this->entryItemSerialRepositoryInterface);

                // Procesar todos los seriales de la misma manera
                foreach ($q['serials'] as $serial) {
                    $itemSerialDTO = new EntryItemSerialDTO([
                        'entry_guide' => $entryGuide,
                        'article' => $guideArticle->getArticle(),
                        'serial' => $serial,
                        'branch_id' => $entryGuide->getBranch()->getId(),
                    ]);

                    $itemSerial = $itemSerialUseCase->execute($itemSerialDTO);
                    $serials[] = $itemSerial;
                }
            }

            $guideArticle->serials = $serials;


            return $guideArticle;
        }, $articlesData);
    }
    private function updateDocumentEntryGuide($shooping, array $data)
    {
        $createDocumentEntryGuideUseCase = new CreateDocumentEntryGuide($this->documentEntryGuideRepositoryInterface, $this->documentTypeRepository);

        $referenceDocumentId = $data['reference_document_id'] ?? ($data['reference_document']['id'] ?? ($data['reference_document'] ?? 0));

        $documentEntryGuide = new DocumentEntryGuideDTO([
            'entry_guide_id' => $shooping->getId(),

            'reference_document_id' => $referenceDocumentId,
            'reference_serie' => $data['reference_serie'] ?? '',
            'reference_correlative' => $data['reference_correlative'] ?? '',
        ]);

        $result = $createDocumentEntryGuideUseCase->execute($documentEntryGuide);
        return $result;
    }
    private function calculateProcessStatus(array $articles, $documentEntryGuide = null): string
    {
        if ($documentEntryGuide && in_array($documentEntryGuide->getReferenceDocument()?->getId(), [1, 7, 8])) {
            return 'completado';
        }

        $totalQuantity = 0;
        $totalSaldo = 0;

        foreach ($articles as $article) {
            // Handle both object and array access if necessary, though strict typing suggests objects here
            // Based on usage, $article is likely an EntryGuideArticle or similar object with getters
            $qty = method_exists($article, 'getQuantity') ? $article->getQuantity() : ($article['quantity'] ?? 0);
            $saldo = method_exists($article, 'getSaldo') ? $article->getSaldo() : ($article['saldo'] ?? 0);

            $totalQuantity += $qty;
            $totalSaldo += $saldo;
        }

        if ($totalQuantity == 0) {
            return 'pendiente';
        }

        if ($totalSaldo == $totalQuantity) {
            return 'pendiente';
        }

        if ($totalSaldo == 0) {
            return 'completado';
        }

        return 'en proceso';
    }
    private function logTransaction($request, $entryGuide, ?string $observations = null): void
    {
        $transactionLogs = new CreateTransactionLogUseCase(
            $this->transactionLogRepositoryInterface,
            $this->userRepository,
            $this->companyRepositoryInterface,
            $this->documentTypeRepository,
            $this->branchRepositoryInterface,
        );

        $transactionDTO = new TransactionLogDTO([
            'user_id' => request()->get('user_id'),
            'role_name' => request()->get('role'),
            'description_log' => 'Guia de Ingreso',
            'observations' => $observations ?? ($request->method() == 'POST' ? 'Registro de documento.' : 'Actualización de documento.'),
            'action' => $request->method(),
            'company_id' => request()->get('company_id'),
            'branch_id' => $entryGuide->getBranch()->getId(),
            'document_type_id' => 15,
            'serie' => $entryGuide->getSerie(),
            'correlative' => $entryGuide->getCorrelativo(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $transactionLogs->execute($transactionDTO);
    }

    public function validateSameCustomer(Request $request): JsonResponse
    {
        $ids = $request->input('ids');

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'Debe enviar un arreglo de IDs válido'], 400);
        }

        $ids = array_map('intval', $ids);

        $isValid = $this->entryGuideRepositoryInterface->allBelongToSameCustomer($ids);

        if (!$isValid) {
            return response()->json(['message' => 'Todos los documentos deben pertenecer al mismo proveedor'], 422);
        }

        $entryGuides = $this->entryGuideRepositoryInterface->findByIds($ids);


        $refSerie = null;
        $refCorrelative = null;
        $refDocumentType = null;
        $entryGuideHeader = null;
        $isIgv = null;
        $date = null;
        $firstIter = true;

        $allDatesSame = true;
        foreach ($entryGuides as $guide) {
            $docEntryGuide = $this->documentEntryGuideRepositoryInterface->findByIdObj($guide->getId());

            if ($firstIter) {
                $refSerie = $docEntryGuide?->getReferenceSerie();
                $refCorrelative = $docEntryGuide?->getReferenceCorrelative();
                $refDocumentType = $docEntryGuide?->getReferenceDocument()?->getId();
                $date = $guide->getDate();
                $isIgv = $guide->getIncludIgv();

                $entryGuideHeader = [
                    'reference_document_id' => $refDocumentType,
                    'reference_serie' => $refSerie,
                    'reference_correlative' => $refCorrelative,
                ];


                $firstIter = false;
            } else {
                if ($docEntryGuide?->getReferenceSerie() !== $refSerie || $docEntryGuide?->getReferenceCorrelative() !== $refCorrelative) {
                    return response()->json(['message' => 'Todos los documentos deben pertenecer a la misma serie y correlativo de referencia'], 422);
                }
                if (($docEntryGuide?->getReferenceDocument()?->getId()) !== $refDocumentType) {
                    return response()->json(['message' => 'Todos los documentos deben tener el mismo tipo de documento de referencia'], 422);
                }
                if ($guide->getDate() !== $date) {
                    $allDatesSame = false;
                }
            }
        }

        if (!$allDatesSame) {
            $date = date('Y-m-d');
        }

        $customerHeader = null;
        $currencyType = null;
        $articleMap = [];

        foreach ($entryGuides as $entryGuide) {
            if ($customerHeader === null) {
                $currencyType = $entryGuide->getCurrency();
                $customerHeader = [
                    'id' => $entryGuide->getCustomer()?->getId(),
                    'name' => $entryGuide->getCustomer()?->getName() ?? $entryGuide->getCustomer()?->getCompanyName() . " " . $entryGuide->getCustomer()?->getLastname() . ' ' . $entryGuide->getCustomer()?->getSecondLastname() . ' / ' . $entryGuide->getCustomer()?->getDocumentNumber() . ' / ' . (
                        collect($entryGuide->getCustomer()?->getAddresses())
                        ->first()
                        ?->getAddress()
                        ?: 'no hay direccion'
                    )
                ];
            }
            $articles = $this->entryGuideArticleRepositoryInterface->findById($entryGuide->getId());

            foreach ($articles as $article) {
                $articleId = $article->getArticle()->getId();

                $qty = (float) $article->getQuantity();
                $saldo = (float) $article->getSaldo();
                $precio = (float) $article->getTotalDescuento();

                // Fallback to article purchase price if 0
                if ($precio == 0) {
                    $precio = (float) $article->getArticle()->getPurchasePrice();
                }

                $subtotal = (float) $article->getSubtotal();
                if ($subtotal == 0 && $precio > 0) {
                    $subtotal = $qty * $precio;
                }

                $total = (float) $article->getTotal();
                if ($total == 0 && $subtotal > 0) {
                    $total = $subtotal;
                }

                $descuento = (float) $article->getDescuento();

                // Si el artículo ya existe en el mapa, sumar las cantidades
                if (isset($articleMap[$articleId])) {
                    $articleMap[$articleId]['quantity'] += $qty;
                    $articleMap[$articleId]['saldo'] += $saldo;
                    $articleMap[$articleId]['subtotal'] += $subtotal;
                    $articleMap[$articleId]['total'] += $total;
                    $articleMap[$articleId]['descuento'] += $descuento;
                } else {
                    // Si es la primera vez que aparece este article_id, agregarlo al mapa
                    $articleMap[$articleId] = [
                        'guide_id' => $entryGuide->getId(),
                        'guide_number' => $entryGuide->getSerie() . '-' . $entryGuide->getCorrelativo(),
                        'article_id' => $articleId,
                        'description' => $article->getDescription(),
                        'quantity' => $qty,
                        'saldo' => $saldo,
                        'cod_fab' => $article->getArticle()->getCodFab(),
                        'subtotal' => $subtotal,
                        'total' => $total,
                        'precio_costo' => $precio,
                        'descuento' => $descuento,
                    ];
                }
            }
        }

        // Convertir el mapa a array indexado
        $aggregated = array_values($articleMap);

        return response()->json([
            'customer' => $customerHeader,
            'articles' => $aggregated,
            'entry_guide' => $entryGuideHeader,
            'currency_type' => $currencyType,
            'date' => $date,
            'is_igv' => $isIgv,
            'nc_document_id' => $entryGuide->getNcDocumentId(),
            'nc_reference_serie' => $entryGuide->getNcReferenceSerie(),
            'nc_reference_correlative' => $entryGuide->getNcReferenceCorrelative(),
        ], 200);
    }
    public function downloadPdf($id)
    {
        try {
            $useCase = new GeneratePDF(
                $this->entryGuideRepositoryInterface,
                app(EntryGuidePDF::class)
            );
            $path = $useCase->execute((int) $id);

            $fullPath = storage_path('app/public/' . $path);

            if (!Storage::disk('public')->exists($path)) {
                return response()->json(['message' => 'PDF no encontrado'], 404);
            }

            $content = Storage::disk('public')->get($path);

            return response()->json([
                'url' => asset('storage/' . $path),
                'fileName' => basename($path),
                'pdf_base64' => base64_encode($content)
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function createDetEntryguidePurchaseOrder($purchaseOrderId, array $entryGuideIds): array
    {
        $createDetEntryguidePurchaseOrderUseCase = new CreateDetEntryguidePurchaseOrderUseCase($this->detEntryguidePurchaseOrderRepository);
        $createdDetails = [];
        foreach ($entryGuideIds as $entryGuideId) {
            $detEntryguidePurchaseOrderDTO = new DetEntryguidePurchaseorderDTO([
                'entry_guide_id' => $purchaseOrderId->getId(),
                'purchase_order_id' => $entryGuideId,
            ]);
            $createdDetails[] = $createDetEntryguidePurchaseOrderUseCase->execute($detEntryguidePurchaseOrderDTO);
        }
        return $createdDetails;
    }

    public function updateStatus(Request $request, int $id)
    {
        $entryGuideUseCase = new FindByIdEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute($id);

        if (!$entryGuide) {
            return response()->json(['message' => 'Guía de ingreso no encontrada'], 404);
        }

        $status = $request->input('status');
        $updateStatusUseCase = new UpdateStatusUseCase($this->entryGuideRepositoryInterface);
        $updateStatusUseCase->execute($id, $status);

        return response()->json(['message' => 'Estado actualizado correctamente']);
    }
    private function createPurchaseFromEntryGuide($entryGuide, array $data)
    {

        $serie = $this->serieRepositoryInterface->findByDocumentType(22, $entryGuide->getBranch()->getId(), null);


        $serieNumber = $serie ? $serie->getSerieNumber() : '';

        $purchaseArticles = array_map(function ($article) {
            return [
                'article_id' => $article['article_id'],
                'description' => $article['description'],
                'cantidad' => $article['quantity'],
                'precio_costo' => $article['quantity'] > 0 ? $article['total'] / $article['quantity'] : 0,
                'descuento' => $article['descuento'] ?? 0,
                'sub_total' => $article['subtotal'] ?? 0,
                'total' => $article['total'] ?? 0,
                'cantidad_update' => $article['quantity'],
                'process_status' => 'facturado',
            ];
        }, $data['entry_guide_articles']);

        $purchaseDTO = new PurchaseDTO([
            'company_id' => $entryGuide->getCompany()->getId(),
            'branch_id' => $entryGuide->getBranch()->getId(),
            'supplier_id' => $entryGuide->getCustomer()->getId(),
            'serie' => $serieNumber,
            'correlative' => '',
            'exchange_type' => null,
            'payment_type_id' => 1,
            'currency_id' => $entryGuide->getCurrency()->getId(),
            'date' => $data['date'] ?? date('Y-m-d'),
            'date_ven' => $data['date'] ?? date('Y-m-d'),
            'days' => 0,
            'observation' => $data['observations'] ?? '',
            'detraccion' => null,
            'fech_detraccion' => null,
            'amount_detraccion' => 0,
            'is_detracion' => false,
            'subtotal' => $data['subtotal'] ?? 0,
            'total_desc' => $data['total_descuento'] ?? 0,
            'inafecto' => 0,
            'igv' => $data['entry_igv'] ?? 0,
            'total' => $data['total'] ?? 0,
            'is_igv' => $data['includ_igv'] ?? true,
            'reference_document_type_id' => $data['document_entry_guide']['reference_document_id'] ?? 1,
            'reference_serie' => $data['document_entry_guide']['reference_serie'] ?? '',
            'reference_correlative' => $data['document_entry_guide']['reference_correlative'] ?? '',
            'det_compras_guia_ingreso' => $purchaseArticles,
            'entry_guide_id' => [$entryGuide->getId()],
        ]);

        $createPurchaseUseCase = new CreatePurchaseUseCase(
            $this->purchaseRepositoryInterface,
            $this->paymentTypeRepositoryInterface,
            $this->branchRepositoryInterface,
            $this->customerRepositoryInterface,
            $this->currencyTypeRepositoryInterface,
            $this->documentNumberGeneratorService,
            $this->documentTypeRepository,
            $this->exchangeRateRepositoryInterface
        );

        return $createPurchaseUseCase->execute($purchaseDTO);
    }

    private function syncPurchaseFromEntryGuide($entryGuide, array $data): void
    {
        $isFactura = (isset($data['document_entry_guide']['reference_document_id']) && $data['document_entry_guide']['reference_document_id'] == 1);
        $iscredito = (isset($data['document_entry_guide']['reference_document_id']) && $data['document_entry_guide']['reference_document_id'] == 7);
        $isdebito = (isset($data['document_entry_guide']['reference_document_id']) && $data['document_entry_guide']['reference_document_id'] == 8);

        $shoppingIncomeGuides = $this->shoppingIncomeGuideRepositoryInterface->findByEntryGuideId($entryGuide->getId());

        if (empty($shoppingIncomeGuides)) {
            if ($isFactura || $iscredito || $isdebito) {
                $this->createPurchaseFromEntryGuide($entryGuide, $data);
            }
            return;
        }

        // Si ya hay compras vinculadas, actualizar la primera vinculada (usualmente es 1:1 o consolidado)
        foreach ($shoppingIncomeGuides as $sig) {
            $purchaseId = $sig->getPurchaseId();
            $this->updateAssociatedPurchase($purchaseId, $data);
        }
    }

    private function updateAssociatedPurchase(int $purchaseId, array $data): void
    {
        $allSigs = $this->shoppingIncomeGuideRepositoryInterface->findById($purchaseId);
        $entryGuideIds = array_map(fn($sig) => $sig->getEntryGuideId(), $allSigs);

        $consolidatedArticles = [];
        $totalSubtotal = 0;
        $totalIgv = 0;
        $totalTotal = 0;
        $totalDescuento = 0;

        foreach ($entryGuideIds as $egId) {
            $guide = $this->entryGuideRepositoryInterface->findById($egId);
            $articles = $this->entryGuideArticleRepositoryInterface->findById($egId);

            foreach ($articles as $article) {
                $articleId = $article->getArticle()->getId();
                if (!isset($consolidatedArticles[$articleId])) {
                    $consolidatedArticles[$articleId] = [
                        'article_id' => $articleId,
                        'description' => $article->getDescription(),
                        'cantidad' => 0,
                        'precio_costo' => $article->getTotalDescuento() ?: (float)$article->getArticle()->getPurchasePrice(),
                        'descuento' => 0,
                        'sub_total' => 0,
                        'total' => 0,
                        'cantidad_update' => 0,
                        'process_status' => 'completado',
                    ];
                }
                $consolidatedArticles[$articleId]['cantidad'] += (float)$article->getQuantity();
                $consolidatedArticles[$articleId]['cantidad_update'] += (float)$article->getQuantity();
                $consolidatedArticles[$articleId]['descuento'] += (float)$article->getDescuento();
                $consolidatedArticles[$articleId]['sub_total'] += (float)$article->getSubtotal();
                $consolidatedArticles[$articleId]['total'] += (float)$article->getTotal();
            }
        }

        foreach ($consolidatedArticles as $art) {
            $totalSubtotal += $art['sub_total'];
            $totalTotal += $art['total'];
            $totalDescuento += $art['descuento'];
        }

        $totalIgv = 0;
        $referenceSerie = '';
        $referenceCorrelative = '';
        $currencyId = 1;

        foreach ($entryGuideIds as $egId) {
            $docEntryGuide = $this->documentEntryGuideRepositoryInterface->findByIdObj($egId);
            $guide = $this->entryGuideRepositoryInterface->findById($egId);

            if ($egId == $data['id']) {
                $totalIgv += (float)($data['entry_igv'] ?? 0);
                $referenceSerie = $data['document_entry_guide']['reference_serie'] ?? '';
                $referenceCorrelative = $data['document_entry_guide']['reference_correlative'] ?? '';
                $currencyId = $guide->getCurrency()->getId();
            } else {
            }
        }

        $isIgv = $data['includ_igv'] ?? true;
        if ($totalIgv == 0 && $isIgv) {
            $totalIgv = $totalTotal - $totalSubtotal;
        }

        $purchase = $this->purchaseRepositoryInterface->findById($purchaseId);

        $purchaseDTO = new PurchaseDTO([
            'company_id' => $purchase->getCompanyId(),
            'branch_id' => $purchase->getBranch()->getId(),
            'supplier_id' => $purchase->getSupplier()->getId(),
            'serie' => $purchase->getSerie(),
            'correlative' => $purchase->getCorrelative(),
            'exchange_type' => $purchase->getExchangeType(),
            'payment_type_id' => $purchase->getPaymentType()->getId(),
            'currency_id' => $purchase->getCurrency()->getId(),
            'date' => $purchase->getDate(),
            'date_ven' => $purchase->getDateVen(),
            'days' => $purchase->getDays(),
            'observation' => $purchase->getObservation(),
            'detraccion' => $purchase->getDetraccion(),
            'fech_detraccion' => $purchase->getFechDetraccion(),
            'amount_detraccion' => $purchase->getAmountDetraccion(),
            'is_detracion' => $purchase->getIsDetracion(),
            'subtotal' => $totalSubtotal,
            'total_desc' => $totalDescuento,
            'inafecto' => $purchase->getInafecto(),
            'igv' => $totalIgv,
            'total' => $totalTotal,
            'is_igv' => $isIgv,
            'reference_document_type_id' => $data['document_entry_guide']['reference_document_id'] ?? 1,
            'reference_serie' => $referenceSerie ?: $purchase->getReferenceSerie(),
            'reference_correlative' => $referenceCorrelative ?: $purchase->getReferenceCorrelative(),
            'det_compras_guia_ingreso' => array_values($consolidatedArticles),
            'entry_guide_id' => $entryGuideIds,
        ]);

        $updatePurchaseUseCase = new UpdatePurchaseUseCase(
            $this->purchaseRepositoryInterface,
            $this->paymentTypeRepositoryInterface,
            $this->branchRepositoryInterface,
            $this->customerRepositoryInterface,
            $this->currencyTypeRepositoryInterface,
            $this->documentNumberGeneratorService,
            $this->documentTypeRepository,
            $this->exchangeRateRepositoryInterface
        );

        $updatePurchaseUseCase->execute($purchaseDTO, $purchaseId);
    }
    public function excelDowload()
    {
        $entryGuides = $this->entryGuideRepositoryInterface->findAllExcel();
        return Excel::download(new ExcelEntryGuide($entryGuides, "Reporte de Guías de Ingreso"), 'entry_guides.xlsx');
    }
}

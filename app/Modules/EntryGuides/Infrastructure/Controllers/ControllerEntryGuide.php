<?php

namespace App\Modules\EntryGuides\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DocumentEntryGuide\application\DTOS\DocumentEntryGuideDTO;
use App\Modules\DocumentEntryGuide\application\UseCases\CreateDocumentEntryGuide;
use App\Modules\DocumentEntryGuide\Domain\Interface\DocumentEntryGuideRepositoryInterface;
use App\Modules\DocumentEntryGuide\Infrastructure\Resource\DocumentEntryGuideResource;
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
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuidePDF;
use App\Modules\EntryItemSerial\Application\DTOS\EntryItemSerialDTO;
use App\Modules\EntryItemSerial\Infrastructure\Resource\EntryItemSerialResource;
use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;
use App\Modules\EntryItemSerial\Application\UseCases\CreateEntryItemSerialUseCase;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ControllerEntryGuide extends Controller
{

    public function __construct(
        private readonly EntryGuideRepositoryInterface        $entryGuideRepositoryInterface,
        private readonly CompanyRepositoryInterface           $companyRepositoryInterface,
        private readonly BranchRepositoryInterface            $branchRepositoryInterface,
        private readonly CustomerRepositoryInterface          $customerRepositoryInterface,
        private readonly EntryGuideArticleRepositoryInterface $entryGuideArticleRepositoryInterface,
        private readonly EntryItemSerialRepositoryInterface   $entryItemSerialRepositoryInterface,
        private readonly IngressReasonRepositoryInterface    $ingressReasonRepositoryInterface,
        private readonly ArticleRepositoryInterface          $articleRepositoryInterface,
        private readonly DocumentNumberGeneratorService      $documentNumberGeneratorService,
        private readonly TransactionLogRepositoryInterface   $transactionLogRepositoryInterface,
        private readonly UserRepositoryInterface             $userRepository,
        private readonly DocumentTypeRepositoryInterface     $documentTypeRepository,
        private readonly DocumentEntryGuideRepositoryInterface $documentEntryGuideRepositoryInterface,
    ) {}

    public function index(Request $request): JsonResponse
    {

        $serie = $request->query('serie');
        $correlativo = $request->query('correlativo');



        $entryGuideUseCase = new FindAllEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuides = $entryGuideUseCase->execute($serie, $correlativo);

        $result = [];

        foreach ($entryGuides as $entryGuide) {
            $articles = $this->entryGuideArticleRepositoryInterface->findById($entryGuide->getId());
            $serialsByArticle = $this->entryItemSerialRepositoryInterface->findSerialsByEntryGuideId($entryGuide->getId());
            $documentEntryGuide = $this->documentEntryGuideRepositoryInterface->findById($entryGuide->getId());

            $articlesWithSerials = array_map(function ($article) use ($serialsByArticle, $documentEntryGuide) {
                $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
                $article->document_entry_guide = $documentEntryGuide;
                return $article;
            }, $articles);



            $response = (new EntryGuideResource($entryGuide))->resolve();
            $response['articles'] = EntryGuideArticleResource::collection($articlesWithSerials)->resolve();
            $response['document_entry_guide'] = DocumentEntryGuideResource::collection($documentEntryGuide)->resolve();
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

            $articlesWithSerials = array_map(function ($article) use ($serialsByArticle) {
                $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
                return $article;
            }, $articles);

            $response = (new EntryGuideResource($entryGuide))->resolve();
            $response['articles'] = EntryGuideArticleResource::collection($articlesWithSerials)->resolve();
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
        $documentEntryGuide = $this->documentEntryGuideRepositoryInterface->findById($entryGuide->getId());

        $entryArticles = array_map(function ($article) use ($serialsByArticle, $documentEntryGuide) {
            $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
            $article->document_entry_guide = $documentEntryGuide;
            return $article;
        }, $entryArticles);

        $response = (new EntryGuideResource($entryGuide))->resolve();
        $response['articles'] = EntryGuideArticleResource::collection($entryArticles)->resolve();
        $response['document_entry_guide'] = DocumentEntryGuideResource::collection($documentEntryGuide)->resolve();

        return response()->json($response, 200);
    }

    public function store(EntryGuideRequest $request): JsonResponse
    {
        $entryGuideDTO = new EntryGuideDTO($request->validated());
        $entryGuideUseCase = new CreateEntryGuideUseCase(
            $this->entryGuideRepositoryInterface,
            $this->companyRepositoryInterface,
            $this->branchRepositoryInterface,
            $this->customerRepositoryInterface,
            $this->ingressReasonRepositoryInterface,
            $this->documentNumberGeneratorService,
        );
        $entryGuide = $entryGuideUseCase->execute($entryGuideDTO);

        $entryGuideArticle = $this->createEntryGuideArticles($entryGuide, $request->validated()['entry_guide_articles']);
        $documentEntryGuide = $this->updateDocumentEntryGuide($entryGuide, $request->validated()['document_entry_guide']);
        $this->logTransaction($request, $entryGuide);

        $response = (new EntryGuideResource($entryGuide))->resolve();
        $response['articles'] = EntryGuideArticleResource::collection($entryGuideArticle)->resolve();
        $response['document_entry_guide'] = DocumentEntryGuideResource::collection($documentEntryGuide)->resolve();

        return response()->json($response, 201);
    }

    public function update(UpdateGuideRequest $request, $id): JsonResponse
    {
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
        );

        $entryGuide = $entryGuideUseCase->execute($entryGuideDTO, $id);

        $this->entryItemSerialRepositoryInterface->deleteByIdEntryItemSerial($entryGuide->getId());
        $this->entryGuideArticleRepositoryInterface->deleteByEntryGuideId($entryGuide->getId());

        $entryGuideArticle = $this->createEntryGuideArticles($entryGuide, $request->validated()['entry_guide_articles']);

        $this->logTransaction($request, $entryGuide);

        $response = (new EntryGuideResource($entryGuide))->resolve();
        $response['articles'] = EntryGuideArticleResource::collection($entryGuideArticle)->resolve();

        return response()->json($response, 200);
    }
    private function createEntryGuideArticles($entryGuide, array $articlesData): array
    {

        $createEntryGuideArticleUseCase = new CreateEntryGuideArticle($this->entryGuideArticleRepositoryInterface, $this->articleRepositoryInterface);
        return array_map(function ($q) use ($entryGuide, $createEntryGuideArticleUseCase) {
            $entryGuideArticleDTO = new EntryGuideArticleDTO([
                'entry_guide_id' => $entryGuide->getId(),
                'article_id' => $q['article_id'],
                'description' => $q['description'],
                'quantity' => $q['quantity'],
            ]);
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
    private function updateDocumentEntryGuide($shooping, array $data): array
    {
        $createDocumentEntryGuideUseCase = new CreateDocumentEntryGuide($this->documentEntryGuideRepositoryInterface);

        return array_map(function ($data) use ($shooping, $createDocumentEntryGuideUseCase) {
            $documentEntryGuide = new DocumentEntryGuideDTO([
                'entry_guide_id' => $shooping->getId(),
                'guide_serie_supplier' => $data['guide_serie_supplier'] ?? '',
                'guide_correlative_supplier' => $data['guide_correlative_supplier'] ?? '',
                'invoice_serie_supplier' => $data['invoice_serie_supplier'] ?? '',
                'invoice_correlative_supplier' => $data['invoice_correlative_supplier'] ?? '',
            ]);

            $result = $createDocumentEntryGuideUseCase->execute($documentEntryGuide);
            return $result;
        }, $data);
    }


    private function logTransaction($request, $entryGuide): void
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

        $customerHeader = null;
        foreach ($entryGuides as $entryGuide) {
            if ($customerHeader === null) {
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
                $key = $article->getArticle()->getId();
                if (!isset($aggregated[$key])) {
                    $aggregated[$key] = [
                        'article_id' => $key,
                        'description' => $article->getDescription(),
                        'quantity' => $article->getQuantity(),
                        'cod_fab' => $article->getArticle()->getCodFab(),

                    ];
                } else {
                    $aggregated[$key]['quantity'] += $article->getQuantity();
                }
            }
        }

        return response()->json([
            'customer' => $customerHeader,
            'articles' => array_values($aggregated)
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

            return response()->download(
                $fullPath,
                basename($path),
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

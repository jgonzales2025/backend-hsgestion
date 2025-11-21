<?php

namespace App\Modules\EntryGuides\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
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
    ) {}

    public function index(): JsonResponse
    {
        $entryGuideUseCase = new FindAllEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuides = $entryGuideUseCase->execute();

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
        $entryArticles = array_map(function ($article) use ($serialsByArticle) {
            $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
            return $article;
        }, $entryArticles);

        $response = (new EntryGuideResource($entryGuide))->resolve();
        $response['articles'] = EntryGuideArticleResource::collection($entryArticles)->resolve();

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

        $this->logTransaction($request, $entryGuide);

        $response = (new EntryGuideResource($entryGuide))->resolve();
        $response['articles'] = EntryGuideArticleResource::collection($entryGuideArticle)->resolve();

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
}

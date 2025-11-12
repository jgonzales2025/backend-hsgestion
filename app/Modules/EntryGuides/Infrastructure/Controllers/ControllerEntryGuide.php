<?php

namespace App\Modules\EntryGuides\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
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
    ) {
    }

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

        $entryArticles = $this->entryGuideArticleRepositoryInterface->findById($entryGuide->getId());
        $serialsByArticle = $this->entryItemSerialRepositoryInterface->findSerialsByEntryGuideId($entryGuide->getId());
        $entryArticles = array_map(function ($article) use ($serialsByArticle) {
            $article->serials = $serialsByArticle[$article->getArticle()->getId()] ?? [];
            return $article;
        }, $entryArticles);

        return response()->json(
            [
                'entryGuide' => (new EntryGuideResource($entryGuide))->resolve(),
                'articles' => EntryGuideArticleResource::collection($entryArticles)->resolve()
            ],
            200
        );
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

        return response()->json(
            [
                'entryGuide' => (new EntryGuideResource($entryGuide))->resolve(),
                'articles' => EntryGuideArticleResource::collection($entryGuideArticle)->resolve()
            ],
            201
        );
    }

    public function update(UpdateGuideRequest $request, $id): JsonResponse
    {
        $entryGuideUseCase = new FindByIdEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute($id);

        if (!$entryGuide) {
            return response()->json(['message' => ' no encontrada'], 404);
        }

        $entryGuideDTO = new EntryGuideDTO($request->validated());
        $entryGuideUseCase = new UpdateEntryGuideUseCase(
            $this->entryGuideRepositoryInterface,
            $this->companyRepositoryInterface,
            $this->branchRepositoryInterface,
            $this->customerRepositoryInterface,
        );

        $entryGuide = $entryGuideUseCase->execute($entryGuideDTO, $id);

        $this->entryGuideArticleRepositoryInterface->deleteByEntryGuideId($entryGuide->getId());



        $entryGuideArticle = $this->createEntryGuideArticles($entryGuide, $request->validated()['entry_guide_articles']);

        $this->entryItemSerialRepositoryInterface->deleteByIdEntryItemSerial($entryGuide->getId());

        $entryGuideArticles = $this->createEntryItemSerialGuideArticle($entryGuide, $request->validated()['entry_item_serial']);

        return response()->json(
            [
                'entryGuide' => (new EntryGuideResource($entryGuide))->resolve(),
                'entry_guide_articles' => EntryGuideArticleResource::collection($entryGuideArticle)->resolve(),
                'entry_item_serials' => EntryItemSerialResource::collection($entryGuideArticles)->resolve()

            ],
            201
        );

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
                foreach ($q['serials'] as $serial) {
                    $itemSerialDTO = new EntryItemSerialDTO([
                        'entry_guide' => $entryGuide,
                        'article' => $guideArticle,
                        'serial' => $serial
                    ]);
                    $itemSerialUseCase = new CreateEntryItemSerialUseCase($this->entryItemSerialRepositoryInterface);
                    $itemSerial = $itemSerialUseCase->execute($itemSerialDTO);
                    $serials[] = $itemSerial;
                }
            }
            $guideArticle->serials = $serials;

            return $guideArticle;
        }, $articlesData);
    }
    private function createEntryItemSerialGuideArticle($sale, array $entryGuideArticle): array
    {

        $createSaleArticleUseCase = new CreateEntryItemSerialUseCase($this->entryItemSerialRepositoryInterface);

        return array_map(function ($q) use ($sale, $createSaleArticleUseCase) {
            $saleArticleDTO = new EntryItemSerialDTO([
                'entry_guide_id' => $sale->getId(),
                'article_id' => $q['article_id'],
                'serial' => $q['serial'],
            ]);

            return $createSaleArticleUseCase->execute($saleArticleDTO);
        }, $entryGuideArticle);
    }
}

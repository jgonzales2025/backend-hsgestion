<?php

namespace App\Modules\DispatchNotes\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DispatchArticle\Application\DTOS\DispatchArticleDTO;
use App\Modules\DispatchArticle\Application\UseCase\CreateDispatchArticleUseCase;
use App\Modules\DispatchArticle\Domain\Entities\DispatchArticle;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use App\Modules\DispatchNotes\application\DTOS\DispatchNoteDTO;
use App\Modules\DispatchNotes\application\UseCases\CreateDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindAllDispatchNotesUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindByIdDispatchNoteUseCase;
use App\Modules\DispatchNotes\application\UseCases\UpdateDispatchNoteUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Requests\RequestStore;
use App\Modules\DispatchNotes\Infrastructure\Requests\RequestUpdate;
use App\Modules\DispatchNotes\Infrastructure\Resource\DispatchNoteResource;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use Illuminate\Http\JsonResponse;

class DispatchNotesController extends Controller
{
    public function __construct(
        private readonly DispatchNotesRepositoryInterface $dispatchNoteRepository,
        private readonly CompanyRepositoryInterface $companyRepositoryInterface,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly SerieRepositoryInterface $serieRepositoryInterface,
        private readonly EmissionReasonRepositoryInterface $emissionReasonRepositoryInterface,
        private readonly TransportCompanyRepositoryInterface $transportCompany,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepositoryInterface,
        private readonly DriverRepositoryInterface $driverRepositoryInterface,
        private readonly DispatchArticleRepositoryInterface $dispatchArticleRepositoryInterface,
    ) {
    }

    public function index(): JsonResponse
    {

        $dispatchNoteUseCase = new FindAllDispatchNotesUseCase($this->dispatchNoteRepository);
        $dispatchNotes = $dispatchNoteUseCase->execute();

        $result = [];
        foreach ($dispatchNotes as $articlesNote) {
            $dispatch = $this->dispatchArticleRepositoryInterface->findById($articlesNote->getId());
            $result[] = [
                'dispatchNote' => (new DispatchNoteResource($articlesNote))->resolve(),
                'dispatchArticle' => DispatchArticleResource::collection($dispatch)->resolve()
            ];
        }

        return response()->json($result, 200);
    }
    public function store(RequestStore $store): JsonResponse
    {
        $dispatchNotesDTO = new DispatchNoteDTO($store->validated());
        $dispatchNoteUseCase = new CreateDispatchNoteUseCase($this->dispatchNoteRepository, $this->companyRepositoryInterface, $this->branchRepository, $this->serieRepositoryInterface, $this->emissionReasonRepositoryInterface, $this->transportCompany, $this->documentTypeRepositoryInterface, $this->driverRepositoryInterface);


        $dispatchNotesDTO->pdf = '1234';
        $dispatchNotes = $dispatchNoteUseCase->execute($dispatchNotesDTO);


        $createSaleArticleUseCase = new CreateDispatchArticleUseCase($this->dispatchArticleRepositoryInterface);
        $saleArticles = array_map(function ($article) use ($dispatchNotes, $createSaleArticleUseCase) {
            $saleArticleDTO = new DispatchArticleDTO([
                'dispatch_id' => $dispatchNotes->getId(),
                'article_id' => $article['article_id'],
                'quantity' => $article['quantity'],
                'weight' => $article['weight'],
                'saldo' => $article['saldo'],
                'name' => $article['name'],
                'subtotal_weight' => $article['subtotal_weight']
            ]);

            return $createSaleArticleUseCase->execute($saleArticleDTO);
        }, $store->validated()['dispatch_articles']);

        return response()->json(
            [
                'sale' => (new DispatchNoteResource($dispatchNotes))->resolve(),
                'articles' => DispatchArticleResource::collection($saleArticles)->resolve()
            ],
            201
        );
    }
    public function show($id): JsonResponse
    {

        $dispatchNoteUseCase = new FindByIdDispatchNoteUseCase($this->dispatchNoteRepository);
        $dispatchNotes = $dispatchNoteUseCase->execute($id);

        $dispatchArticle = $this->dispatchArticleRepositoryInterface->findById($dispatchNotes->getId());


        return response()->json(
            [
                'dispatchNote' => (new DispatchNoteResource($dispatchNotes))->resolve(),
                'dispatchArticle' => DispatchArticleResource::collection($dispatchArticle)->resolve()
            ]
        );
    }

    public function update(RequestUpdate $store, $id): JsonResponse
    {
      $saleUseCase = new FindByIdDispatchNoteUseCase($this->dispatchNoteRepository);
        $dispatchNote = $saleUseCase->execute($id);

        if (!$dispatchNote) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

      

        $dispatchNotesDTO = new DispatchNoteDTO($store->validated());
        $dispatchNoteUseCase = new UpdateDispatchNoteUseCase($this->dispatchNoteRepository, $this->companyRepositoryInterface, $this->branchRepository, $this->serieRepositoryInterface, $this->emissionReasonRepositoryInterface, $this->transportCompany, $this->documentTypeRepositoryInterface, $this->driverRepositoryInterface);
        $dispatchNotes = $dispatchNoteUseCase->execute($dispatchNotesDTO, $dispatchNote);
        

        $this->dispatchArticleRepositoryInterface->deleteBySaleId($dispatchNotes->getId());

        $dispatchArticle = $this->createDispatchArticles($dispatchNotes, $store->validated()['dispatch_articles']);

        return response()->json(
            [
                'sale' => (new DispatchNoteResource($dispatchNotes))->resolve(),
                'articles' => DispatchArticleResource::collection($dispatchArticle)->resolve()
            ],
            201
        );
    }
    private function createDispatchArticles($sale, array $articlesData): array
    {

        $createSaleArticleUseCase = new CreateDispatchArticleUseCase($this->dispatchArticleRepositoryInterface);

        return array_map(function ($article) use ($sale, $createSaleArticleUseCase) {
            $saleArticleDTO = new DispatchArticleDTO([
                'dispatch_id' => $sale->getId(),
                'article_id' => $article['article_id'],
                'quantity' => $article['quantity'],
                'weight' => $article['weight'],
                'saldo' => $article['saldo'],
                'name' => $article['name'],
                'subtotal_weight' => $article['subtotal_weight']
            ]);

            return $createSaleArticleUseCase->execute($saleArticleDTO);
        }, $articlesData);
    }
}
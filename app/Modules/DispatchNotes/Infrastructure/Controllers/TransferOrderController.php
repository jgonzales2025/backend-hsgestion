<?php

namespace App\Modules\DispatchNotes\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DispatchArticle\Application\DTOS\DispatchArticleDTO;
use App\Modules\DispatchArticle\Application\UseCase\CreateDispatchArticleUseCase;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use App\Modules\DispatchArticleSerial\Application\DTOs\DispatchArticleSerialDTO;
use App\Modules\DispatchArticleSerial\Application\UseCases\CreateDispatchArticleSerialUseCase;
use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;
use App\Modules\DispatchNotes\application\DTOS\TransferOrderDTO;
use App\Modules\DispatchNotes\application\UseCases\CreateTransferOrderUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Resource\TransferOrderResource;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use App\Modules\DispatchNotes\Infrastructure\Requests\StoreTransferOrderRequest;

class TransferOrderController extends Controller
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository,
        private TransferOrderRepositoryInterface $transferOrderRepository,
        private BranchRepositoryInterface $branchRepository,
        private EmissionReasonRepositoryInterface $emissionReasonRepository,
        private DocumentNumberGeneratorService $documentNumberGeneratorService,
        private DispatchArticleRepositoryInterface $dispatchArticleRepositoryInterface,
        private DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository,
        private ArticleRepositoryInterface $articleRepository,
        private DispatchNotesRepositoryInterface $dispatchNoteRepository,
    ){}

    public function store(StoreTransferOrderRequest $request)
    {
        $transferOrderDTO = new TransferOrderDTO($request->validated());
        $transferOrderUseCase = new CreateTransferOrderUseCase(
            $this->transferOrderRepository,
            $this->emissionReasonRepository,
            $this->branchRepository,
            $this->companyRepository,
            $this->documentNumberGeneratorService,
        );
        $transferOrder = $transferOrderUseCase->execute($transferOrderDTO);

        $createDispatchArticleUseCase = new CreateDispatchArticleUseCase($this->dispatchArticleRepositoryInterface);
        $dispatchArticles = array_map(function ($article) use ($transferOrder, $createDispatchArticleUseCase) {
            $dispatchArticleDTO = new DispatchArticleDTO([
                'dispatch_id' => $transferOrder->getId(),
                'article_id' => $article['article_id'],
                'quantity' => $article['quantity'],
                'weight' => $article['weight'] ?? null,
                'saldo' => $article['saldo'] ?? null,
                'name' => $article['name'],
                'subtotal_weight' => $article['subtotal_weight'] ?? null,
            ]);

            $dispatchArticle = $createDispatchArticleUseCase->execute($dispatchArticleDTO);

            // Array para almacenar los seriales
             $serials = [];

            if (!empty($article['serials'])) {
                foreach ($article['serials'] as $serial) {
                    $dispatchArticleSerialDTO = new DispatchArticleSerialDTO([
                        'dispatch_note_id' => $transferOrder->getId(),
                        'article_id' => $dispatchArticle->getArticleID(),
                        'serial' => $serial,
                        'emission_reasons_id' => $transferOrder->getEmissionReason()->getId(),
                        'status' => 2,
                        'origin_branch' => $transferOrder->getBranch(),
                        'destination_branch' => $transferOrder->getDestinationBranch(),
                    ]);
                    $dispatchArticleSerialUseCase = new CreateDispatchArticleSerialUseCase($this->dispatchArticleSerialRepository, $this->articleRepository, $this->branchRepository, $this->dispatchNoteRepository);
                    $dispatchArticleSerial = $dispatchArticleSerialUseCase->execute($dispatchArticleSerialDTO);
                    $serials[] = $dispatchArticleSerial;
                }
            }

            //Agregar los seriales al objeto dispatchArticle
            $dispatchArticle->serials = $serials;

            return $dispatchArticle;
        }, array: $request->validated()['dispatch_articles']);

        $response = (new TransferOrderResource($transferOrder))->resolve();
        $response['dispatch_articles'] = DispatchArticleResource::collection($dispatchArticles)->resolve();

        return response()->json($response);
    }
}
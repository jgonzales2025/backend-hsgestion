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
use App\Modules\DispatchArticleSerial\Application\UseCases\FindSerialsByTransferOrderIdUseCase;
use App\Modules\DispatchArticleSerial\Application\UseCases\UpdateStatusSerialEntryUseCase;
use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;
use App\Modules\DispatchNotes\Application\DTOs\TransferOrderDTO;
use App\Modules\DispatchNotes\Application\DTOs\UpdateTransferOrderDTO;
use App\Modules\DispatchNotes\Application\UseCases\CreateTransferOrderUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindAllConsignationUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindAllTransferOrdersUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindByIdTransferOrderUseCase;
use App\Modules\DispatchNotes\Application\UseCases\ToInvalidateTransferOrderUseCase;
use App\Modules\DispatchNotes\Application\UseCases\UpdateStatusTransferOrderUseCase;
use App\Modules\DispatchNotes\Application\UseCases\UpdateTransferOrderUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Resource\TransferOrderResource;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use App\Modules\DispatchNotes\Infrastructure\Requests\StoreTransferOrderRequest;
use App\Modules\DispatchNotes\Infrastructure\Requests\UpdateTransferOrderRequest;
use App\Modules\DispatchNotes\Infrastructure\Resource\ConsignationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $companyId = request()->get('company_id');

        $description = $request->query('description');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;
        $emissionReasonId = $request->query('emission_reason_id');

        $transferOrdersUseCase = new FindAllTransferOrdersUseCase($this->transferOrderRepository);
        $transferOrders = $transferOrdersUseCase->execute($companyId, $description, $startDate, $endDate, $status, $emissionReasonId);

        $result = [];
        foreach ($transferOrders as $transferOrder) {
            $articles = $this->dispatchArticleRepositoryInterface->findByDispatchNoteId($transferOrder->getId());
            $serialsByArticle = $this->dispatchArticleSerialRepository->findSerialsByTransferOrderId($transferOrder->getId());

            $articlesWithSerials = array_map(function ($article) use ($serialsByArticle) {
                $article->serials = $serialsByArticle[$article->getArticleId()] ?? [];
                return $article;
            }, $articles);

            $response = (new TransferOrderResource($transferOrder))->resolve();
            $response['dispatch_articles'] = DispatchArticleResource::collection($articlesWithSerials)->resolve();
            $result[] = $response;
        }

        return new JsonResponse([
            'data' => $result,
            'current_page' => $transferOrders->currentPage(),
            'per_page' => $transferOrders->perPage(),
            'total' => $transferOrders->total(),
            'last_page' => $transferOrders->lastPage(),
            'next_page_url' => $transferOrders->nextPageUrl(),
            'prev_page_url' => $transferOrders->previousPageUrl(),
            'first_page_url' => $transferOrders->url(1),
            'last_page_url' => $transferOrders->url($transferOrders->lastPage()),
        ]);
    }

    public function indexConsignations(Request $request): JsonResponse
    {
        $companyId = request()->get('company_id');

        $description = $request->query('description');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;
        $emissionReasonId = $request->query('emission_reason_id');

        $transferOrdersUseCase = new FindAllConsignationUseCase($this->transferOrderRepository);
        $transferOrders = $transferOrdersUseCase->execute($companyId, $description, $startDate, $endDate, $status, $emissionReasonId);

        $result = [];
        foreach ($transferOrders as $transferOrder) {
            $articles = $this->dispatchArticleRepositoryInterface->findByDispatchNoteId($transferOrder->getId());
            $serialsByArticle = $this->dispatchArticleSerialRepository->findSerialsByTransferOrderId($transferOrder->getId());

            $articlesWithSerials = array_map(function ($article) use ($serialsByArticle) {
                $article->serials = $serialsByArticle[$article->getArticleId()] ?? [];
                return $article;
            }, $articles);

            $response = (new ConsignationResource($transferOrder))->resolve();
            $response['dispatch_articles'] = DispatchArticleResource::collection($articlesWithSerials)->resolve();
            $result[] = $response;
        }

        return new JsonResponse([
            'data' => $result,
            'current_page' => $transferOrders->currentPage(),
            'per_page' => $transferOrders->perPage(),
            'total' => $transferOrders->total(),
            'last_page' => $transferOrders->lastPage(),
            'next_page_url' => $transferOrders->nextPageUrl(),
            'prev_page_url' => $transferOrders->previousPageUrl(),
            'first_page_url' => $transferOrders->url(1),
            'last_page_url' => $transferOrders->url($transferOrders->lastPage()),
        ]);
    }

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

    public function show(int $id)
    {
        $findByIdTransferOrderUseCase = new FindByIdTransferOrderUseCase($this->transferOrderRepository);
        $transferOrder = $findByIdTransferOrderUseCase->execute($id);

        if (!$transferOrder) {
            return response()->json(['message' => 'Orden de salida no encontrada'], 404);
        }

        $articles = $this->dispatchArticleRepositoryInterface->findByDispatchNoteId($transferOrder->getId());
        $serialsByArticle = $this->dispatchArticleSerialRepository->findSerialsByTransferOrderId($transferOrder->getId());

        $articlesWithSerials = array_map(function ($article) use ($serialsByArticle) {
            $article->serials = $serialsByArticle[$article->getArticleId()] ?? [];
            return $article;
        }, $articles);

        $response = (new TransferOrderResource($transferOrder))->resolve();
        $response['dispatch_articles'] = DispatchArticleResource::collection($articlesWithSerials)->resolve();

        return response()->json($response);
    }

    public function update(int $id, UpdateTransferOrderRequest $request): JsonResponse
    {
        $transferOrderUseCase = new FindByIdTransferOrderUseCase($this->transferOrderRepository);
        $transferOrder = $transferOrderUseCase->execute($id);

        if (!$transferOrder) {
            return response()->json(['message' => 'Orden de salida no encontrada'], 404);
        }

        if ($transferOrder->getStage() == 1) {
            return response()->json(['message' => 'No se puede modificar una orden de salida que ya ha sido recibida.'], 400);
        }

        $updateTransferOrderDTO = new UpdateTransferOrderDTO($request->validated());
        $updateTransferOrderUseCase = new UpdateTransferOrderUseCase($this->transferOrderRepository, $this->branchRepository, $this->emissionReasonRepository);
        $updateTransferOrderUseCase->execute($id, $updateTransferOrderDTO);

        $serials = $this->dispatchArticleSerialRepository->findSerialsByTransferOrderId($id);
        $this->dispatchArticleSerialRepository->deleteByTransferOrderId($id, $serials);
        $this->dispatchArticleRepositoryInterface->deleteBySaleId($id);

        $createDispatchArticleUseCase = new CreateDispatchArticleUseCase($this->dispatchArticleRepositoryInterface);
        array_map(function ($article) use ($transferOrder, $createDispatchArticleUseCase) {
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
        }, array: $request->validated()['dispatch_articles']);

        return response()->json(['message' => 'Orden de salida actualizada correctamente']);
    }

    public function updateStatusTransferOrder(int $id, Request $request)
    {
        $findByIdTransferOrderUseCase = new FindByIdTransferOrderUseCase($this->transferOrderRepository);
        $transferOrder = $findByIdTransferOrderUseCase->execute($id);

        if (!$transferOrder) {
            return response()->json(['message' => 'Orden de salida no encontrada'], 404);
        }

        if ($transferOrder->getStage() == 1) {
            return response()->json(['message' => 'No se puede modificar una orden de salida que ya ha sido recibida.'], 400);
        }

        $validatedData = $request->validate([
            'destination_branch_id' => 'required',
            'dispatch_articles' => 'required|array|min:1',
            'dispatch_articles.*.article_id' => 'required|integer',
            'dispatch_articles.*.serials' => 'required|array|min:1',
            'dispatch_articles.*.serials.*' => 'string|distinct'
        ]);

        $updateStatusUseCase = new UpdateStatusTransferOrderUseCase($this->transferOrderRepository);
        $updateStatusUseCase->execute($id);

        $updateSerialEntryUseCase = new UpdateStatusSerialEntryUseCase($this->dispatchArticleSerialRepository);

        foreach ($validatedData['dispatch_articles'] as $article) {
            foreach ($article['serials'] as $serial) {
                $updateSerialEntryUseCase->execute($validatedData['destination_branch_id'], $serial);
            }
        }

        return response()->json(['message' => 'Orden de salida recepcionada correctamente.'], 200);
    }

    public function toInvalidateTransferOrder(int $id)
    {
        $findByIdTransferOrderUseCase = new FindByIdTransferOrderUseCase($this->transferOrderRepository);
        $transferOrder = $findByIdTransferOrderUseCase->execute($id);

        if (!$transferOrder) {
            return response()->json(['message' => 'Orden de salida no encontrada'], 404);
        }

        if ($transferOrder->getStage() == 1) {
            return response()->json(['message' => 'No se puede anular una orden de salida que ya ha sido recibida.'], 400);
        }

        $serialsUseCase = new FindSerialsByTransferOrderIdUseCase($this->dispatchArticleSerialRepository);
        $serialsByArticle = $serialsUseCase->execute($id);
        $serials = array_merge(...array_values($serialsByArticle));

        $this->dispatchArticleSerialRepository->deleteByTransferOrderId($id, $serials, $transferOrder->getBranch()->getId());

        $toInvalidateUseCase = new ToInvalidateTransferOrderUseCase($this->transferOrderRepository);
        $toInvalidateUseCase->execute($id);

        return response()->json(['message' => 'Orden de salida invalidada correctamente.'], 200);
    }
}
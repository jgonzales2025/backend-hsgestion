<?php

namespace App\Modules\DispatchNotes\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\Customer\Infrastructure\Resources\CustomerCompanyResource;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
use App\Modules\CustomerAddress\Infrastructure\Resources\CustomerAddressResource;
use App\Modules\DispatchArticle\Application\DTOS\DispatchArticleDTO;
use App\Modules\DispatchArticle\Application\UseCase\CreateDispatchArticleUseCase;
use App\Modules\DispatchArticle\Domain\Entities\DispatchArticle;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use App\Modules\DispatchArticleSerial\Application\DTOs\DispatchArticleSerialDTO;
use App\Modules\DispatchArticleSerial\Application\UseCases\CreateDispatchArticleSerialUseCase;
use App\Modules\DispatchArticleSerial\Application\UseCases\UpdateStatusSerialEntryUseCase;
use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;
use App\Modules\DispatchNotes\application\DTOS\DispatchNoteDTO;
use App\Modules\DispatchNotes\application\UseCases\CreateDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindAllDispatchNotesUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindByDocumentSale;
use App\Modules\DispatchNotes\Application\UseCases\FindByIdDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\GenerateDispatchNotePdfUseCase;
use App\Modules\DispatchNotes\application\UseCases\UpdateDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\UpdateStatusDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\UpdateStatusDispatchUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Requests\RequestStore;
use App\Modules\DispatchNotes\Infrastructure\Requests\RequestUpdate;
use App\Modules\DispatchNotes\Infrastructure\Resource\DispatchNoteResource;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Modules\User\Application\UseCases\UpdateStatusUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        private readonly GenerateDispatchNotePdfUseCase $generatePdfUseCase,
        private readonly CustomerRepositoryInterface $customerRepositoryInterface,
        private readonly CustomerAddressRepositoryInterface $customerAddressRepositoryInterface,
        private readonly TransactionLogRepositoryInterface $transactionLogRepositoryInterface,
        private readonly UserRepositoryInterface $userRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository,
        private readonly ArticleRepositoryInterface $articleRepository
    ) {}

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
        $dispatchUseCase = new FindByDocumentSale($this->dispatchNoteRepository);
        $dispatchNote = $dispatchUseCase->execute($store->validated()['doc_referencia'], $store->validated()['num_referencia']);

        if ($dispatchNote) {
            return response()->json([
                'message' => 'Esta venta ya tiene una guía de remisión asignada.'
            ], 400);
        }

        $dispatchNotesDTO = new DispatchNoteDTO($store->validated());
        $dispatchNoteUseCase = new CreateDispatchNoteUseCase(
            $this->dispatchNoteRepository,
            $this->companyRepositoryInterface,
            $this->branchRepository,
            $this->serieRepositoryInterface,
            $this->emissionReasonRepositoryInterface,
            $this->transportCompany,
            $this->documentTypeRepositoryInterface,
            $this->driverRepositoryInterface,
            $this->customerRepositoryInterface,
        );



        $dispatchNotes = $dispatchNoteUseCase->execute($dispatchNotesDTO);

        $status = $dispatchNotes->getEmissionReason()->getId() == 1 ? 0 : 2;
        $createDispatchArticleUseCase = new CreateDispatchArticleUseCase($this->dispatchArticleRepositoryInterface);
        $dispatchArticles = array_map(function ($article) use ($dispatchNotes, $createDispatchArticleUseCase, $status) {
            $dispatchArticleDTO = new DispatchArticleDTO([
                'dispatch_id' => $dispatchNotes->getId(),
                'article_id' => $article['article_id'],
                'quantity' => $article['quantity'],
                'weight' => $article['weight'],
                'saldo' => $article['saldo'],
                'name' => $article['name'],
                'subtotal_weight' => $article['subtotal_weight']
            ]);

            $dispatchArticle = $createDispatchArticleUseCase->execute($dispatchArticleDTO);

            // Array para almacenar los seriales
            $serials = [];

            if (!empty($article['serials'])) {
                foreach ($article['serials'] as $serial) {
                    $dispatchArticleSerialDTO = new DispatchArticleSerialDTO([
                        'dispatch_note_id' => $dispatchNotes->getId(),
                        'article_id' => $dispatchArticle->getArticleID(),
                        'serial' => $serial,
                        'emission_reasons_id' => $dispatchNotes->getEmissionReason()->getId(),
                        'status' => $status,
                        'origin_branch' => $dispatchNotes->getBranch(),
                        'destination_branch' => $dispatchNotes->getDestinationBranch(),
                    ]);
                    $dispatchArticleSerialUseCase = new CreateDispatchArticleSerialUseCase($this->dispatchArticleSerialRepository, $this->articleRepository, $this->branchRepository, $this->dispatchNoteRepository);
                    $dispatchArticleSerial = $dispatchArticleSerialUseCase->execute($dispatchArticleSerialDTO);
                    $serials[] = $dispatchArticleSerial;
                }
            }

            //Agregar los seriales al objeto dispatchArticle
            $dispatchArticle->serials = $serials;

            return $dispatchArticle;
        }, array: $store->validated()['dispatch_articles']);

        $this->logTransaction($store, $dispatchNotes);

        return response()->json(
            [
                'dispatchNote' => (new DispatchNoteResource($dispatchNotes))->resolve(),
                'articles' => DispatchArticleResource::collection($dispatchArticles)->resolve()
            ],
            201
        );
    }
    public function generate(int $id)
    {
        try {
            $pdfContent = $this->generatePdfUseCase->execute((int) $id);

            $filename = 'factura_electronica_' . $id . '.pdf';

            return response()->streamDownload(function () use ($pdfContent) {
                echo $pdfContent;
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show($id): JsonResponse
    {

        $dispatchNoteUseCase = new FindByIdDispatchNoteUseCase($this->dispatchNoteRepository);
        $dispatchNotes = $dispatchNoteUseCase->execute($id);

        if (!$dispatchNotes) {
            return response()->json(['message' => 'Nota de despacho no encontrada'], 404);
        }

        $dispatchArticle = $this->dispatchArticleRepositoryInterface->findById($dispatchNotes->getId());

        $serialsByArticle = $this->dispatchArticleSerialRepository->findSerialsByTransferOrderId($dispatchNotes->getId());

        $articlesWithSerials = array_map(function ($article) use ($serialsByArticle) {
            $article->serials = $serialsByArticle[$article->getArticleId()] ?? [];
            return $article;
        }, $dispatchArticle);

        return response()->json(
            [
                'dispatchNote' => (new DispatchNoteResource($dispatchNotes))->resolve(),
                'dispatchArticle' => DispatchArticleResource::collection($articlesWithSerials)->resolve()
            ]
        );
    }

    public function update(RequestUpdate $store, $id): JsonResponse
    {
        if ($store->validated()['emission_reason_id'] == 1) {
            return response()->json(['message' => 'No se puede modificar una nota de despacho emitida con motivo de venta.'], 400);
        }

        $saleUseCase = new FindByIdDispatchNoteUseCase($this->dispatchNoteRepository);
        $dispatchNote = $saleUseCase->execute($id);

        if (!$dispatchNote) {
            return response()->json(['message' => 'Guía de remisión no encontrada'], 404);
        }

        if ($dispatchNote->getEmissionReason()->getId() == 1) {
            return response()->json(['message' => 'No se puede modificar una nota de despacho emitida con motivo de venta.'], 400);
        }

        $dispatchNotesDTO = new DispatchNoteDTO($store->validated());
        $dispatchNoteUseCase = new UpdateDispatchNoteUseCase(
            $this->dispatchNoteRepository,
            $this->companyRepositoryInterface,
            $this->branchRepository,
            $this->serieRepositoryInterface,
            $this->emissionReasonRepositoryInterface,
            $this->transportCompany,
            $this->documentTypeRepositoryInterface,
            $this->driverRepositoryInterface,
            $this->customerRepositoryInterface,
        );
        $dispatchNotes = $dispatchNoteUseCase->execute($dispatchNotesDTO, $dispatchNote);


        $this->dispatchArticleRepositoryInterface->deleteBySaleId($dispatchNotes->getId());

        $dispatchArticle = $this->createDispatchArticles($dispatchNotes, $store->validated()['dispatch_articles']);

        $this->logTransaction($store, $dispatchNotes);

        return response()->json(
            [
                'sale' => (new DispatchNoteResource($dispatchNotes))->resolve(),
                'articles' => DispatchArticleResource::collection($dispatchArticle)->resolve()
            ],
            201
        );
    }

    public function traerProovedores()
    {
        // Obtener company_id del usuario logeado

        $payload = JWTAuth::parseToken()->payload();

        $loggedCompanyId = $payload->get('company_id');

        // Traer todos los proveedores excepto el de la compañía logeada
        $proveedores = EloquentCustomer::where('record_type_id', 1)
            ->where('id', '!=', $loggedCompanyId)
            ->get();

        $adres = EloquentCustomerAddress::where('id', '!=', $loggedCompanyId)
            ->get();

        return response()->json([
            'customer' => $proveedores,
            'addresses' => $adres,

        ]);
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
    public function updateStatus(int $id, Request $request)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $status = $validatedData['status'];

        $updateStatusUseCase = new UpdateStatusDispatchNoteUseCase($this->dispatchNoteRepository);
        $updateStatusUseCase->execute($id, $status);

        return response()->json(['message' => 'Status actualizado'], 200);
    }

    private function logTransaction($request, $dispatchNote): void
    {
        $transactionLogs = new CreateTransactionLogUseCase(
            $this->transactionLogRepositoryInterface,
            $this->userRepository,
            $this->companyRepositoryInterface,
            $this->documentTypeRepository,
            $this->branchRepository,
        );

        $transactionDTO = new TransactionLogDTO([
            'user_id' => request()->get('user_id'),
            'role_name' => request()->get('role'),
            'description_log' => 'Guia de Remisión',
            'action' => $request->method(),
            'company_id' => request()->get('company_id'),
            'branch_id' => $dispatchNote->getBranch()->getId(),
            'document_type_id' => 9,
            'serie' => $dispatchNote->getSerie(),
            'correlative' => $dispatchNote->getCorrelativo(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $transactionLogs->execute($transactionDTO);
    }
}

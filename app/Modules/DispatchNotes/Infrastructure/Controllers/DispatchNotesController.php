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
use App\Modules\DispatchNotes\Application\DTOs\DispatchNoteDTO;
use App\Modules\DispatchNotes\Application\UseCases\CreateDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindAllDispatchNotesUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindByDocumentSale;
use App\Modules\DispatchNotes\Application\UseCases\FindByIdDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\GenerateDispatchNotePdfUseCase;
use App\Modules\DispatchNotes\Application\UseCases\UpdateDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\UpdateStatusDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\UpdateStatusDispatchUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Persistence\ExcelDispatch;
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
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService
    ) {}

    public function index(Request $request): JsonResponse
    {

        $description = $request->query('description');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;
        $emissionReasonId = $request->query('emission_reason_id') !== null ? (int) $request->query('emission_reason_id') : null;
        $estadoSunat = $request->query('estado_sunat');

        $dispatchUseCase = new FindAllDispatchNotesUseCase($this->dispatchNoteRepository);
        $dispatchNotes = $dispatchUseCase->execute($description, $status, $emissionReasonId, $estadoSunat);

        $result = [];
        foreach ($dispatchNotes as $articlesNote) {
            $dispatch = $this->dispatchArticleRepositoryInterface->findById($articlesNote->getId());
            $result[] = [
                'dispatchNote' => (new DispatchNoteResource($articlesNote))->resolve(),
                'dispatchArticle' => DispatchArticleResource::collection($dispatch)->resolve()
            ];
        }

        return new JsonResponse([
            'data' => $result,
            'current_page' => $dispatchNotes->currentPage(),
            'per_page' => $dispatchNotes->perPage(),
            'total' => $dispatchNotes->total(),
            'last_page' => $dispatchNotes->lastPage(),
            'next_page_url' => $dispatchNotes->nextPageUrl(),
            'prev_page_url' => $dispatchNotes->previousPageUrl(),
            'first_page_url' => $dispatchNotes->url(1),
            'last_page_url' => $dispatchNotes->url($dispatchNotes->lastPage()),
        ]);
    }

    public function store(RequestStore $store): JsonResponse
    {
        return DB::transaction(function () use ($store) {
            // Solo verificar duplicados si tanto doc_referencia como num_referencia están presentes
            if (!empty($store->validated()['doc_referencia']) && !empty($store->validated()['num_referencia'])) {
                $dispatchUseCase = new FindByDocumentSale($this->dispatchNoteRepository);
                $dispatchNote = $dispatchUseCase->execute($store->validated()['doc_referencia'], $store->validated()['num_referencia']);

                if ($dispatchNote) {
                    return response()->json([
                        'message' => 'Esta venta ya tiene una guía de remisión asignada.'
                    ], 400);
                }
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
                $this->documentNumberGeneratorService
            );



            $dispatchNotes = $dispatchNoteUseCase->execute($dispatchNotesDTO);

            $dispatchArticles = $this->createDispatchArticles($dispatchNotes, $store->validated()['dispatch_articles']);

            $this->logTransaction($store, $dispatchNotes);

            return response()->json(
                [
                    'dispatchNote' => (new DispatchNoteResource($dispatchNotes))->resolve(),
                    'articles' => DispatchArticleResource::collection($dispatchArticles)->resolve()
                ],
                201
            );
        });
    }
    public function generate(int $id)
    {
        try {
            $pdfContent = $this->generatePdfUseCase->execute((int) $id);

            // We need to fetch the dispatch note to get the correct filename components if possible, 
            // but generatePdfUseCase only returns string content. 
            // Assuming we want to keep a consistent naming convention or use a generic one if object not available here easily without query.
            // However, looking at index method, we can see how to retrieve it if needed, but let's stick to the ID based or simple one if that's what was there, 
            // OR better, let's fetch it to be consistent with other controllers if we want the series/correlative.
            // But the previous code just used 'factura_electronica_' . $id. 
            // Let's stick to a safe name with the ID as before but saving it.

            // To be more consistent with the user request "make it the same", 
            // I should probably try to use the series/correlative if I can, but the tool use case returns raw content.
            // The previous code used: $filename = 'factura_electronica_' . $id . '.pdf';
            // I will use that for now but ensuring no spaces (which it doesn't have).

            $filename = 'factura_electronica_' . $id . '.pdf';
            $path = 'pdf/' . $filename;

            Storage::disk('public')->put($path, $pdfContent);

            return response()->json([
                'url' => asset('storage/' . $path),
                'fileName' => $filename,
                'pdf_base64' => base64_encode($pdfContent)
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
        return DB::transaction(function () use ($store, $id) {

            $saleUseCase = new FindByIdDispatchNoteUseCase($this->dispatchNoteRepository);
            $dispatchNote = $saleUseCase->execute($id);

            if (!$dispatchNote) {
                return response()->json(['message' => 'Guía de remisión no encontrada'], 404);
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

            $serials = $this->dispatchArticleSerialRepository->findSerialsByTransferOrderId($id);
            $this->dispatchArticleSerialRepository->deleteByTransferOrderId($id, $serials);
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
        });
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
    private function createDispatchArticles($dispatchNote, array $articlesData): array
    {
        $status = $dispatchNote->getEmissionReason()->getId() == 1 ? 0 : 2;
        $createDispatchArticleUseCase = new CreateDispatchArticleUseCase($this->dispatchArticleRepositoryInterface);

        return array_map(function ($article) use ($dispatchNote, $createDispatchArticleUseCase, $status) {
            $dispatchArticleDTO = new DispatchArticleDTO([
                'dispatch_id' => $dispatchNote->getId(),
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
                        'dispatch_note_id' => $dispatchNote->getId(),
                        'article_id' => $dispatchArticle->getArticleID(),
                        'serial' => $serial,
                        'emission_reasons_id' => $dispatchNote->getEmissionReason()->getId(),
                        'status' => $status,
                        'origin_branch' => $dispatchNote->getBranch(),
                        'destination_branch' => $dispatchNote->getDestinationBranch(),
                    ]);
                    $dispatchArticleSerialUseCase = new CreateDispatchArticleSerialUseCase($this->dispatchArticleSerialRepository, $this->articleRepository, $this->branchRepository, $this->dispatchNoteRepository);
                    $dispatchArticleSerial = $dispatchArticleSerialUseCase->execute($dispatchArticleSerialDTO);
                    $serials[] = $dispatchArticleSerial;
                }
            }

            //Agregar los seriales al objeto dispatchArticle
            $dispatchArticle->serials = $serials;

            return $dispatchArticle;
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

    private function logTransaction($request, $dispatchNote, ?string $observations = null): void
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
            'observations' => $observations ?? ($request->method() == 'POST' ? 'Registro de documento.' : 'Actualización de documento.'),
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
    public function excelDowload()
    {
        $findAllExcel = $this->dispatchNoteRepository->findAllExcel(null, null, null);
        return Excel::download(new ExcelDispatch(collect($findAllExcel), "guia de remision"), "guia_de_remision.xlsx");
    }
}

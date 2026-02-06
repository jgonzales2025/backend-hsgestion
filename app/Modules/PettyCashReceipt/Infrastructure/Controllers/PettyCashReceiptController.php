<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;
use App\Modules\PettyCashReceipt\Application\DTOS\PettyCashReceiptDTO;
use App\Modules\PettyCashReceipt\Application\UseCases\CreatePettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\ExportPettyCashToExcelUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\FindAllPettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\FindByIdPettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\SelectProcedureUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\UpdatePettyCashReceiptStatusUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\UpdatePettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;
use App\Modules\PettyCashReceipt\Infrastructure\Request\CreatePettyCashReceiptRequest;
use App\Modules\PettyCashReceipt\Infrastructure\Request\UpdatePettyCashReceiptRequest;
use App\Modules\PettyCashReceipt\Infrastructure\Resource\PettyCashReceiptResource;
use App\Modules\PettyCashReceipt\Infrastructure\Exports\PettyCashProcedureExport;
use App\Modules\PettyCashReceipt\Infrastructure\Persistence\PettyCashProcedureExport as PersistencePettyCashProcedureExport;
use App\Modules\PettyCashReceipt\Infrastructure\Persistence\CobranzaDetalleExport;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Excel as MaatwebsiteExcel;
use Maatwebsite\Excel\Facades\Excel;

class PettyCashReceiptController extends Controller
{

    public function __construct(
        private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly TransactionLogRepositoryInterface $transactionLogRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}
    public function index(Request $request): JsonResponse
    {
        $filter = $request->query('search');
        $is_active = $request->query('is_active');

        $currency_type = $request->query('document_type');
        $fecha_inicio = $request->query('fecha_inicio');
        $fecha_fin = $request->query('fecha_fin');

        $pettyCashReceiptsUseCase = new FindAllPettyCashReceiptUseCase($this->pettyCashReceiptRepository);
        $pettyCashReceipts = $pettyCashReceiptsUseCase->execute($filter, $currency_type, $is_active, $fecha_inicio, $fecha_fin);

        // Return paginated response with navigation URLs
        return new JsonResponse([
            'data' => PettyCashReceiptResource::collection($pettyCashReceipts->items())->resolve(),
            'current_page' => $pettyCashReceipts->currentPage(),
            'per_page' => $pettyCashReceipts->perPage(),
            'total' => $pettyCashReceipts->total(),
            'last_page' => $pettyCashReceipts->lastPage(),
            'next_page_url' => $pettyCashReceipts->nextPageUrl(),
            'prev_page_url' => $pettyCashReceipts->previousPageUrl(),
            'first_page_url' => $pettyCashReceipts->url(1),
            'last_page_url' => $pettyCashReceipts->url($pettyCashReceipts->lastPage()),
        ]);
    }
    public function show(int $id): JsonResponse
    {
        $pettyCashReceiptUseCase = new FindByIdPettyCashReceiptUseCase($this->pettyCashReceiptRepository);
        $pettyCashReceipt = $pettyCashReceiptUseCase->execute($id);

        return response()->json(new PettyCashReceiptResource($pettyCashReceipt), 200);
    }
    public function store(CreatePettyCashReceiptRequest $request)
    {
        $eloquentCreatePettyCash = new PettyCashReceiptDTO($request->validated());

        $eloquentCreatePettyCashReceiptUseCase = new CreatePettyCashReceiptUseCase(
            $this->pettyCashReceiptRepository,
            $this->branchRepository,
            $this->currencyTypeRepository,
            $this->documentNumberGeneratorService,
            $this->documentTypeRepository,
            $this->pettyCashMotiveRepository
        );
        $eloquentCreatePettyCash = $eloquentCreatePettyCashReceiptUseCase->execute($eloquentCreatePettyCash);

        $this->logTransaction($request, $eloquentCreatePettyCash);

        return response()->json(new PettyCashReceiptResource($eloquentCreatePettyCash), 201);
    }
    public function update(int $id, UpdatePettyCashReceiptRequest $request)
    {
        $eloquentCreatePettyCash = new PettyCashReceiptDTO($request->validated());

        $updatePettyCashReceiptUseCase = new UpdatePettyCashReceiptUseCase(
            $this->pettyCashReceiptRepository,
            $this->branchRepository,
            $this->currencyTypeRepository,
            $this->documentTypeRepository,
            $this->pettyCashMotiveRepository
        );
        $updatePettyCashReceipt = $updatePettyCashReceiptUseCase->execute($eloquentCreatePettyCash, $id);

        $this->logTransaction($request, $updatePettyCashReceipt);

        return response()->json(new PettyCashReceiptResource($updatePettyCashReceipt), 200);
    }
    public function updateStatus(int $pettyId, Request $request): JsonResponse
    {
        $validateStatus = $request->validate([
            'status' => 'required|integer|in:0,1'
        ]);
        $status = $validateStatus['status'];


        $updatePettyCashReceiptUseCase = new UpdatePettyCashReceiptStatusUseCase(
            $this->pettyCashReceiptRepository,
        );
        $updatePettyCashReceiptUseCase->execute($pettyId, $status);

        return response()->json(["message" => "estado actualizado"], 200);
    }

    private function paginateStoredProcedure(array $items, $perPage = 10): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $items = collect($items);

        $pagedItems = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return  new LengthAwarePaginator(
            $pagedItems,
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function selectProcedure(Request $request): JsonResponse
    {
        $companyId = request()->get('company_id');

        $validated = $request->validate([

            'fecha' => 'nullable|date',
            'fechaU' => 'nullable|date',
            'nrocliente' => 'nullable|integer',
            'pcodsuc' => 'required|integer',
            'ptippag' => 'nullable|integer',
            'pcodban' => 'nullable|integer',
            'pnroope' => 'nullable|string',
            'ptipdoc' => 'nullable|integer',
            'pserie' => 'nullable|string',
            'pcorrelativo' => 'nullable|string',
        ]);

        $validated['cia'] = $companyId;

        $selectProcedureUseCase = new SelectProcedureUseCase(
            $this->pettyCashReceiptRepository
        );

        $data = $selectProcedureUseCase->execute(
            $validated['cia'],
            $validated['fecha'] ?? '',
            $validated['fechaU'] ?? '',
            $validated['nrocliente'],
            $validated['pcodsuc'],
            $validated['ptippag'],
            $validated['pcodban'],
            $validated['pnroope'],
            $validated['ptipdoc'],
            $validated['pserie'] ?? '',
            $validated['pcorrelativo'] ?? ''
        );

        $datos = $this->paginateStoredProcedure($data, 10);

        return response()->json([
            'data'           => $datos->items(),
            'current_page'   => $datos->currentPage(),
            'per_page'       => $datos->perPage(),
            'total'          => $datos->total(),
            'last_page'      => $datos->lastPage(),
            'next_page_url'  => $datos->nextPageUrl(),
            'last_page_url' => $datos->url($datos->lastPage()),
            'first_page_url' => $datos->url(1),
            'prev_page_url'  => $datos->previousPageUrl(),

        ]);
    }

    public function exportExcel(Request $request)
    {
        $companyId = request()->get('company_id');

        $validated = $request->validate([
            'fecha' => 'nullable|date',
            'fechaU' => 'nullable|date',
            'nrocliente' => 'nullable|integer',
            'pcodsuc' => 'required|integer',
            'ptippag' => 'nullable|integer',
            'pcodban' => 'nullable|integer',
            'pnroope' => 'nullable|string',
            'ptipdoc' => 'nullable|integer',
            'pserie' => 'nullable|string',
            'pcorrelativo' => 'nullable|string',
        ]);

        $validated['cia'] = $companyId;

        // Obtener datos del procedimiento almacenado
        $selectProcedureUseCase = new SelectProcedureUseCase(
            $this->pettyCashReceiptRepository
        );

        $data = $selectProcedureUseCase->execute(
            $validated['cia'],
            $validated['fecha'] ?? '',
            $validated['fechaU'] ?? '',
            $validated['nrocliente'],
            $validated['pcodsuc'],
            $validated['ptippag'],
            $validated['pcodban'],
            $validated['pnroope'],
            $validated['ptipdoc'],
            $validated['pserie'],
            $validated['pcorrelativo']
        );

        // Stream directo XLSX para evitar cualquier mezcla de salida y asegurar binario correcto
        $fileName = 'parte_caja_' . now()->format('Y-m-d_His') . '.xlsx';
        $export = new PersistencePettyCashProcedureExport($data);
        return Excel::download($export, $fileName, MaatwebsiteExcel::XLSX);
    }

    public function exportExcelCobranzaDetalle(Request $request)
    {
        $companyId = request()->get('company_id');

        $validated = $request->validate([
            'fecha' => 'nullable|date',
            'fechaU' => 'nullable|date',
            'nrocliente' => 'nullable|integer',
            'pcodsuc' => 'required|integer',
            'ptippag' => 'nullable|integer',
            'pcodban' => 'nullable|integer',
            'pnroope' => 'nullable|string',
            'ptipdoc' => 'nullable|integer',
            'pserie' => 'nullable|string',
            'pcorrelativo' => 'nullable|string',
        ]);

        $validated['cia'] = $companyId;

        // Obtener datos del procedimiento almacenado
        $selectProcedureUseCase = new SelectProcedureUseCase(
            $this->pettyCashReceiptRepository
        );

        $data = $selectProcedureUseCase->execute(
            $validated['cia'],
            $validated['fecha'] ?? '',
            $validated['fechaU'] ?? '',
            $validated['nrocliente'],
            $validated['pcodsuc'],
            $validated['ptippag'],
            $validated['pcodban'],
            $validated['pnroope'],
            $validated['ptipdoc'],
            $validated['pserie'],
            $validated['pcorrelativo']
        );

        // Obtener nombre de la compañía
        $companyName = '';
        if ($validated['cia']) {
            $company = $this->companyRepository->findById($validated['cia']);
            if ($company) {
                $companyName = $company->getCompanyName();
            }
        }

        // Stream directo XLSX para evitar cualquier mezcla de salida y asegurar binario correcto
        $fileName = 'parte_caja_' . now()->format('Y-m-d_His') . '.xlsx';
        $export = new CobranzaDetalleExport(
            $data,
            $companyName,
            $validated['fecha'] ?? null,
            $validated['fechaU'] ?? null
        );
        return Excel::download($export, $fileName, MaatwebsiteExcel::XLSX);
    }


    public function listartCobranzaDetalle(Request $request)
    {
        $companyId = request()->get('company_id');

        $validated = $request->validate([
            'fecha' => 'nullable|date',
            'fechaU' => 'nullable|date',
            'nrocliente' => 'nullable|integer',
            'pcodsuc' => 'required|integer',
            'ptippag' => 'nullable|integer',
            'pcodban' => 'nullable|integer',
            'pnroope' => 'nullable|string',
            'ptipdoc' => 'nullable|integer',
            'pserie' => 'nullable|string',
            'pcorrelativo' => 'nullable|string',
        ]);

        $validated['cia'] = $companyId;



        $data = $this->pettyCashReceiptRepository->cobranzaDetalle(
            $validated['cia'],
            $validated['fecha'] ?? null,
            $validated['fechaU'] ?? null,
            $validated['nrocliente'] ?? 0,
            $validated['pcodsuc'],
            $validated['ptippag'] ?? 0,
            $validated['pcodban'] ?? 0,
            $validated['pnroope'] ?? '',
            $validated['ptipdoc'] ?? 0,
            $validated['pserie'] ?? '',
            $validated['pcorrelativo'] ?? ''
        );

        $data = $this->paginateStoredProcedure($data, 10);

        return response()->json([
            'data'           => $data->items(),
            'current_page'   => $data->currentPage(),
            'per_page'       => $data->perPage(),
            'total'          => $data->total(),
            'last_page'      => $data->lastPage(),
            'next_page_url'  => $data->nextPageUrl(),
            'last_page_url' => $data->url($data->lastPage()),
            'first_page_url' => $data->url(1),
            'prev_page_url'  => $data->previousPageUrl(),

        ]);
    }

    private function logTransaction($request, $pettyCash, ?string $observations = null): void
    {
        $transactionLogs = new CreateTransactionLogUseCase(
            $this->transactionLogRepository,
            $this->userRepository,
            $this->companyRepository,
            $this->documentTypeRepository,
            $this->branchRepository
        );

        $transactionDTO = new TransactionLogDTO([
            'user_id' => request()->get('user_id'),
            'role_name' => request()->get('role'),
            'description_log' => 'Caja Chica',
            'observations' => $observations ?? ($request->method() == 'POST' ? 'Registro de documento.' : 'Actualización de documento.'),
            'action' => $request->method(),
            'company_id' => $pettyCash->getCompany(),
            'branch_id' => $pettyCash->getBranch()->getId(),
            'document_type_id' => $pettyCash->getDocumentType()->getId(),
            'serie' => $pettyCash->getSeries(),
            'correlative' => $pettyCash->getCorrelative(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $transactionLogs->execute($transactionDTO);
    }
}

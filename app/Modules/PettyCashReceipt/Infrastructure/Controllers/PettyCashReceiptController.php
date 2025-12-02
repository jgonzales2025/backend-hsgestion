<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;
use App\Modules\PettyCashReceipt\Application\DTOS\PettyCashReceiptDTO;
use App\Modules\PettyCashReceipt\Application\UseCases\CreatePettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\ExportPettyCashToExcelUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\FindAllPettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\FindByIdPettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\application\UseCases\SelectProcedureUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\UpdatePettyCashReceiptStatusUseCase;
use App\Modules\PettyCashReceipt\Application\UseCases\UpdatePettyCashReceiptUseCase;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;
use App\Modules\PettyCashReceipt\Infrastructure\Request\CreatePettyCashReceiptRequest;
use App\Modules\PettyCashReceipt\Infrastructure\Request\UpdatePettyCashReceiptRequest;
use App\Modules\PettyCashReceipt\Infrastructure\Resource\PettyCashReceiptResource;
use App\Modules\PettyCashReceipt\Infrastructure\Exports\PettyCashProcedureExport;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PettyCashReceiptController extends Controller
{

    public function __construct(
        private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveRepository
    ) {}
    public function index(Request $request): array
    {
        $filter = $request->query('filter');

        $pettyCashReceiptsUseCase = new FindAllPettyCashReceiptUseCase($this->pettyCashReceiptRepository);
        $pettyCashReceipts = $pettyCashReceiptsUseCase->execute($filter);

        return PettyCashReceiptResource::collection($pettyCashReceipts)->resolve();
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

        return response()->json($data);
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
            $validated['pserie'] ?? '',
            $validated['pcorrelativo'] ?? ''
        );

        // Stream directo XLSX para evitar cualquier mezcla de salida y asegurar binario correcto
        $fileName = 'parte_caja_' . now()->format('Y-m-d_His') . '.xlsx';
        $export = new \App\Modules\PettyCashReceipt\Infrastructure\Persistence\PettyCashProcedureExport($data);
        return Excel::download($export, $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }
}

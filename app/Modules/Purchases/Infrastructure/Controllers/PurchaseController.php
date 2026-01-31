<?php

namespace App\Modules\Purchases\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Collections\Domain\Entities\Collection;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Purchases\Application\DTOS\PurchaseDTO;
use App\Modules\Purchases\Application\UseCases\CreatePurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\FindAllPurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\FindByIdPurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\UpdatePurchaseUseCase;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;
use App\Modules\Purchases\Application\UseCases\FindAllDetallePurchaseUseCase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Request\CreatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Request\UpdatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Resource\DetailedPurchaseResource;
use App\Modules\Purchases\Infrastructure\Resource\PurchaseResource;
use Illuminate\Http\JsonResponse;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Purchases\Infrastructure\Persistence\PurchasesExport;
use App\Modules\Purchases\Infrastructure\Persistence\GenericExport;
use App\Modules\Purchases\Infrastructure\Persistence\DetailedPurchaseExport;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly ExchangeRateRepositoryInterface $exchangeRateRepository,
        private readonly TransactionLogRepositoryInterface $transactionLogRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $company_id = request()->get('company_id');
        $description = $request->query('description');
        $marca = $request->query('marca') ? (int) $request->query('marca') : null;
        $cod_producto = $request->query('cod_producto') ? (int) $request->query('cod_producto') : null;
        $sucursal = $request->query('sucursal') ? (int) $request->query('sucursal') : null;
        $fecha_inicio = $request->query('fecha_inicio');
        $fecha_fin = $request->query('fecha_fin');

        $useCase = new FindAllPurchaseUseCase($this->purchaseRepository);
        $purchases = $useCase->execute($company_id, $description, $marca, $cod_producto, $sucursal, $fecha_inicio, $fecha_fin, 1);

        $result = PurchaseResource::collection($purchases)->resolve();

        return new JsonResponse([
            'data' => $result,
            'current_page' => $purchases->currentPage(),
            'per_page' => $purchases->perPage(),
            'total' => $purchases->total(),
            'last_page' => $purchases->lastPage(),
            'next_page_url' => $purchases->nextPageUrl(),
            'prev_page_url' => $purchases->previousPageUrl(),
            'first_page_url' => $purchases->url(1),
            'last_page_url' => $purchases->url($purchases->lastPage())
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $findByIdPurchaseUseCase = new FindByIdPurchaseUseCase($this->purchaseRepository);
        $purchase = $findByIdPurchaseUseCase->execute($id);
        if (!$purchase) {
            return response()->json(['message' => 'Compra no encontrada'], 404);
        }

        return response()->json(
            new PurchaseResource($purchase),
            200
        );
    }

    public function store(CreatePurchaseRequest $request): JsonResponse
    {

        $purchaseDTO = new PurchaseDTO($request->validated());
        $cretaePurchaseUseCase = new CreatePurchaseUseCase(
            $this->purchaseRepository,
            $this->paymentTypeRepository,
            $this->branchRepository,
            $this->customerRepository,
            $this->currencyRepository,
            $this->documentNumberGeneratorService,
            $this->documentTypeRepository,
            $this->exchangeRateRepository
        );

        $purchase = $cretaePurchaseUseCase->execute($purchaseDTO);

        $this->logTransaction($request, $purchase);
        return response()->json(
            (new PurchaseResource($purchase))->resolve(),
            201
        );
    }

    public function update(UpdatePurchaseRequest $request, int $id): JsonResponse
    {

        $purchaseDTO = new PurchaseDTO($request->validated());
        $updatePurchaseUseCase = new UpdatePurchaseUseCase(
            $this->purchaseRepository,
            $this->paymentTypeRepository,
            $this->branchRepository,
            $this->customerRepository,
            $this->currencyRepository,
            $this->documentNumberGeneratorService,
            $this->documentTypeRepository,
            $this->exchangeRateRepository
        );
        $purchase = $updatePurchaseUseCase->execute($purchaseDTO, $id);

        if (!$purchase) {
            return response()->json(['message' => 'Compra no encontrada'], 404);
        }

        $this->logTransaction($request, $purchase);

        return response()->json(
            (new PurchaseResource($purchase))->resolve(),
            200
        );
    }

    public function downloadPdf(int $id)
    {
        $purchase = $this->purchaseRepository->dowloadPdf($id);
        if (!$purchase) {
            return response()->json(['message' => 'Compra no encontrada'], 404);
        }
        $company = $this->companyRepository->findById($purchase->getCompanyId());

        $pdf = Pdf::loadView('purchase_pdf', [
            'purchase' => $purchase,
            'company' => $company,
        ]);

        $fileName = $purchase->getSerie() . '_' . $purchase->getCorrelative() . '.pdf';
        $path = 'purchases/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        return response()->json([
            'url' => asset('storage/' . $path),
            'fileName' => $fileName,
            'pdf_base64' => base64_encode($pdf->output())
        ]);
    }

    public function exportExcel(Request $request)
    {
        $description = $request->query('description');
        $num_doc = $request->query('num_doc');
        $id_proveedr = $request->query('supplier_id');

        $purchases = $this->purchaseRepository->findAllExcel($description, $num_doc, $id_proveedr, 1);

        return Excel::download(new PurchasesExport($purchases), 'compras.xlsx');
    }
    public function reporteVentasCompras(Request $request)
    {
        $companyId = request()->get('company_id');

        $validated = $request->validate([
            'date_start' => 'required|string',
            'date_end' => 'required|string',
            'tipo_doc' => 'nullable|integer',
            'nrodoc_cli_pro' => 'nullable|integer',
            'tipo_register' => 'nullable|integer',
        ]);

        $validated['company_id'] = $companyId;

        $purchases = $this->purchaseRepository->sp_registro_ventas_compras(
            $validated['company_id'],
            $validated['date_start'],
            $validated['date_end'],
            $validated['tipo_doc'] ?? 0,
            $validated['nrodoc_cli_pro'] ?? 0,
            $validated['tipo_register'] ?? 2
        );

        $tipoRegister = $validated['tipo_register'] ?? 2;
        $title = $tipoRegister == 1 ? 'REGISTRO DE VENTA' : 'REGISTRO DE COMPRA';
        $fileName = $tipoRegister == 1 ? 'registro_ventas.xlsx' : 'registro_compras.xlsx';

        $company = $this->companyRepository->findById($companyId);
        $companyName = $company ? $company->getCompanyName() : 'EMPRESA';

        return Excel::download(new GenericExport(
            collect($purchases),
            $title,
            $companyName,
            $validated['date_start'],
            $validated['date_end']
        ), $fileName);
    }

    private function logTransaction($request, $purchase, ?string $observations = null): void
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
            'company_id' => $purchase->getCompanyId(),
            'branch_id' => $purchase->getBranch()->getId(),
            'document_type_id' => $purchase->getTypeDocumentId()->getId(),
            'serie' => $purchase->getSerie(),
            'correlative' => $purchase->getCorrelative(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $transactionLogs->execute($transactionDTO);
    }
    public function updateStatus(int $id, Request $request)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $status = $validatedData['status'];


        $this->purchaseRepository->updateStatus($id, $status);

        return response()->json(['message' => 'Status actualizado'], 200);
    }

    public function listarComprasDetalle(Request $request): JsonResponse
    {
        $company_id = request()->get('company_id');
        $description = $request->query('categoria');
        $marca = $request->query('marca') ? (int) $request->query('marca') : null;
        $cod_producto = $request->query('cod_producto') ? (int) $request->query('cod_producto') : null;
        $sucursal = $request->query('sucursal') ? (int) $request->query('sucursal') : null;
        $fecha_inicio = $request->query('fecha_inicio');
        $fecha_fin = $request->query('fecha_fin');

        $useCase = new FindAllDetallePurchaseUseCase($this->purchaseRepository);
        $purchases = $useCase->execute($company_id, $description, $marca, $cod_producto, $sucursal, $fecha_inicio, $fecha_fin, 1);

        $result = DetailedPurchaseResource::collection($purchases)->resolve();

        return new JsonResponse([
            'data' => $result,
            'current_page' => $purchases->currentPage(),
            'per_page' => $purchases->perPage(),
            'total' => $purchases->total(),
            'last_page' => $purchases->lastPage(),
            'next_page_url' => $purchases->nextPageUrl(),
            'prev_page_url' => $purchases->previousPageUrl(),
            'first_page_url' => $purchases->url(1),
            'last_page_url' => $purchases->url($purchases->lastPage())
        ]);
    }
    public function exportarComprasDetalleExcel(Request $request)
    {
        $company_id = $request->input('company_id');
        $categoria = $request->input('categoria');
        $marca = $request->input('marca') ? (int) $request->input('marca') : null;
        $cod_producto = $request->input('cod_producto') ? (int) $request->input('cod_producto') : null;
        $sucursal = $request->input('sucursal') ? (int) $request->input('sucursal') : null;
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        $company = $this->companyRepository->findById($company_id);
        $companyName = $company ? $company->getCompanyName() : '';

        $useCase = new FindAllDetallePurchaseUseCase($this->purchaseRepository);
        $purchases = $useCase->executeExcel($company_id, $categoria, $marca, $cod_producto, $sucursal, $fecha_inicio, $fecha_fin, 1);

        $fileName = 'registro_compras_detallado_' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(
            new DetailedPurchaseExport($purchases, $companyName, $fecha_inicio, $fecha_fin),
            $fileName
        );
    }
}

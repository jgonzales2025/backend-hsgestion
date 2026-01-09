<?php

namespace App\Modules\Purchases\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
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
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Request\CreatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Request\UpdatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Resource\PurchaseResource;
use Illuminate\Http\JsonResponse; 
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; 
use App\Modules\Purchases\Infrastructure\Persistence\PurchasesExport;
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
    ) {}

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $num_doc = $request->query('num_doc');
        $id_proveedr = $request->query('supplier_id');


        $findAllPurchaseUseCase = new FindAllPurchaseUseCase($this->purchaseRepository);
        $purchases = $findAllPurchaseUseCase->execute($description, $num_doc, $id_proveedr);

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
            $this->documentTypeRepository
        );

        $purchase = $cretaePurchaseUseCase->execute($purchaseDTO);
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
            $this->documentTypeRepository
        );
        $purchase = $updatePurchaseUseCase->execute($purchaseDTO, $id);

        if (!$purchase) {
            return response()->json(['message' => 'Compra no encontrada'], 404);
        }

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
        return $pdf->stream($purchase->getSerie() . '_' . $purchase->getCorrelative() . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $description = $request->query('description');
        $num_doc = $request->query('num_doc');
        $id_proveedr = $request->query('supplier_id');

        $purchases = $this->purchaseRepository->findAllExcel($description, $num_doc, $id_proveedr);

        return Excel::download(new PurchasesExport($purchases), 'compras.xlsx');
    }
}

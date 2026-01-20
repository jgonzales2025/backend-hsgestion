<?php

namespace App\Modules\Warranty\Infrastructure\Controller;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\CustomerEmail\Application\UseCases\FindByCustomerIdEmailUseCase;
use App\Modules\CustomerEmail\Domain\Interfaces\CustomerEmailRepositoryInterface;
use App\Modules\CustomerPhone\Application\UseCases\FindByCustomerIdPhoneUseCase;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryItemSerial\Application\UseCases\FindByEntryGuideBySerialUseCase;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\SaleItemSerial\Application\UseCases\FindArticleBySerialUseCase;
use App\Modules\SaleItemSerial\Application\UseCases\FindSaleBySerialUseCase;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;
use App\Modules\Warranty\Application\DTOs\TechnicalSupportDTO;
use App\Modules\Warranty\Application\DTOs\WarrantyDTO;
use App\Modules\Warranty\Application\UseCases\CreateTechnicalSupportUseCase;
use App\Modules\Warranty\Application\UseCases\CreateWarrantyUseCase;
use App\Modules\Warranty\Application\UseCases\FindAllWarrantiesUseCases;
use App\Modules\Warranty\Application\UseCases\FindByIdWarrantyUseCase;
use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;
use App\Modules\Warranty\Infrastructure\Requests\StoreWarrantyRequest;
use App\Modules\Warranty\Infrastructure\Resource\TechnicalSupportResource;
use App\Modules\Warranty\Infrastructure\Resource\WarrantyArticleResource;
use App\Modules\Warranty\Infrastructure\Resource\WarrantyEntryGuideResource;
use App\Modules\Warranty\Infrastructure\Resource\WarrantyResource;
use App\Modules\Warranty\Infrastructure\Resource\WarrantySaleResource;
use App\Modules\WarrantyStatus\Domain\Interfaces\WarrantyStatusRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WarrantyController
{
    public function __construct(
        private readonly WarrantyRepositoryInterface $warrantyRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly EntryGuideRepositoryInterface $entryGuideRepository,
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly WarrantyStatusRepositoryInterface $warrantyStatusRepository,
        private readonly SaleItemSerialRepositoryInterface $saleItemSerialRepository,
        private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly CustomerPhoneRepositoryInterface $customerPhoneRepository,
        private readonly CustomerEmailRepositoryInterface $customerEmailRepository,
    ){}

    public function index(Request $request)
    {
        $description = $request->query('description');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $warrantyStatus = $request->query('warranty_status_id');

        $warrantiesUseCases = new FindAllWarrantiesUseCases($this->warrantyRepository);
        $warranties = $warrantiesUseCases->execute($description, $startDate, $endDate, $warrantyStatus);

        $data = $warranties->map(function ($warranty) {
            if ($warranty->document_type_warranty_id == 1) {
                return new WarrantyResource($warranty, $this->documentTypeRepository);
            } elseif ($warranty->document_type_warranty_id == 2) {
                return new TechnicalSupportResource($warranty);
            }
            return new WarrantyResource($warranty, $this->documentTypeRepository);
        });

        return response()->json([
            'data' => $data,
            'current_page' => $warranties->currentPage(),
            'per_page' => $warranties->perPage(),
            'total' => $warranties->total(),
            'last_page' => $warranties->lastPage(),
            'next_page_url' => $warranties->nextPageUrl(),
            'prev_page_url' => $warranties->previousPageUrl(),
            'first_page_url' => $warranties->url(1),
            'last_page_url' => $warranties->url($warranties->lastPage()),
        ]);
    }

    public function store(StoreWarrantyRequest $request)
    {
        if ($request->validated()['document_type_warranty_id'] == 2) {
            $technicalSupportDTO = new TechnicalSupportDTO($request->validated());
            $technicalSupportUseCase = new CreateTechnicalSupportUseCase($this->warrantyRepository, $this->documentNumberGeneratorService, $this->companyRepository, $this->branchRepository);
            $id = $technicalSupportUseCase->execute($technicalSupportDTO);
            return response()->json(['message' => 'Soporte técnico creado exitosamente', 'id' => $id]);
        } else {
            $warrantyDTO = new WarrantyDTO($request->validated());
            $warrantyUseCase = new CreateWarrantyUseCase($this->warrantyRepository, $this->companyRepository, $this->branchRepository, $this->articleRepository, $this->customerRepository, $this->entryGuideRepository, $this->saleRepository, $this->warrantyStatusRepository, $this->documentNumberGeneratorService);
            $id = $warrantyUseCase->execute($warrantyDTO);
            return response()->json(['message' => 'Garantía creada exitosamente', 'id' => $id]);
        }
    }

    public function show($id)
    {
        $warrantyUseCase = new FindByIdWarrantyUseCase($this->warrantyRepository);
        $warranty = $warrantyUseCase->execute($id);

        if (!$warranty) {
            return response()->json(['message' => 'Garantía no encontrada'], 404);
        }

        if ($warranty->getDocumentTypeWarrantyId() == 2) {
            return response()->json(new TechnicalSupportResource($warranty));
        } else {
            return response()->json(new WarrantyResource($warranty, $this->documentTypeRepository));
        }
    }

    public function findDocumentsBySerial(Request $request)
    {
        $serial = $request->query('serial');
        
        $saleUseCase = new FindSaleBySerialUseCase($this->saleItemSerialRepository);
        $sale = $saleUseCase->execute($serial);

        if (!$sale) {
            return response()->json(['message' => 'No se encontraron documentos para el serial proporcionado'], 404);
        }

        $phonesUseCase = new FindByCustomerIdPhoneUseCase($this->customerPhoneRepository);
        $phones = $phonesUseCase->execute($sale->getCustomer()->getId());

        $emailsUseCase = new FindByCustomerIdEmailUseCase($this->customerEmailRepository);
        $emails = $emailsUseCase->execute($sale->getCustomer()->getId());
        
        $articleUseCase = new FindArticleBySerialUseCase($this->saleItemSerialRepository);
        $article = $articleUseCase->execute($serial); 

        $entryGuideUseCase = new FindByEntryGuideBySerialUseCase($this->entryItemSerialRepository);
        $entryGuide = $entryGuideUseCase->execute($serial);

        return response()->json([
            'sale' => (new WarrantySaleResource($sale, $phones, $emails))->resolve(),
            'article' => (new WarrantyArticleResource($article))->resolve(),
            'entry_guide' => (new WarrantyEntryGuideResource($this->documentTypeRepository, $entryGuide))->resolve()
        ]);
    }

    public function generatePDF(int $id)
    {
        $warrantyUseCase = new FindByIdWarrantyUseCase($this->warrantyRepository);
        $warranty = $warrantyUseCase->execute($id);

        if (!$warranty) {
            return response()->json(['message' => 'Garantía no encontrada'], 404);
        }

        $pdf = Pdf::loadView('warranty', [
            'warranty' => $warranty
        ]);

        $fileName = 'Garantia' . '_' . $warranty->getSerie() . '-' . $warranty->getCorrelative() . '.pdf';

        $path = 'pdf/' . $fileName;
        $content = $pdf->output();
        Storage::disk('public')->put($path, $content);

        return response()->json([
            'url' => asset('storage/' . $path),
            'fileName' => $fileName,
            'pdf_base64' => base64_encode($content)
        ]);
    }
}
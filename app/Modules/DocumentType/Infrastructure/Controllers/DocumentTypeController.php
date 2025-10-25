<?php

namespace App\Modules\DocumentType\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DocumentType\Application\UseCases\FindAllDocumentTypesUseCase;
use App\Modules\DocumentType\Application\UseCases\FindAllForSalesUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\DocumentType\Infrastructure\Resources\DocumentTypeResource;

class DocumentTypeController extends Controller
{

    public function __construct(private readonly DocumentTypeRepositoryInterface $documentTypeRepository){}

    public function index(): array
    {
        $documentTypeUseCase = new FindAllDocumentTypesUseCase($this->documentTypeRepository);
        $documentTypes = $documentTypeUseCase->execute();

        return DocumentTypeResource::collection($documentTypes)->resolve();
    }

    public function indexSales(): array
    {
        $documentTypeUseCase = new FindAllForSalesUseCase($this->documentTypeRepository);
        $documentTypes = $documentTypeUseCase->execute();

        return DocumentTypeResource::collection($documentTypes)->resolve();
    }
}

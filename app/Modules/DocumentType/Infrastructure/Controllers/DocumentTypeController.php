<?php

namespace App\Modules\DocumentType\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DocumentType\Application\UseCases\FindAllDocumentTypesUseCase;
use App\Modules\DocumentType\Application\UseCases\FindAllForDocumentSalesUseCase;
use App\Modules\DocumentType\Application\UseCases\FindAllForEntryGuidesUseCase;
use App\Modules\DocumentType\Application\UseCases\FindAllForInvoicesUseCase;
use App\Modules\DocumentType\Application\UseCases\FindAllForPettyCashUseCase;
use App\Modules\DocumentType\Application\UseCases\FindAllForPurchasesUseCases;
use App\Modules\DocumentType\Application\UseCases\FindAllForSalesUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\DocumentType\Infrastructure\Resources\DocumentTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function indexInvoices(): array
    {
        $documentTypeUseCase = new FindAllForInvoicesUseCase($this->documentTypeRepository);
        $documentTypes = $documentTypeUseCase->execute();

        return DocumentTypeResource::collection($documentTypes)->resolve();
    }

    public function indexPettyCash(): array
    {
        $documentTypeUseCase = new FindAllForPettyCashUseCase($this->documentTypeRepository);
        $documentTypes = $documentTypeUseCase->execute();

        return DocumentTypeResource::collection($documentTypes)->resolve();
    }

    public function indexDocumentSales(): array
    {
        $documentTypeUseCase = new FindAllForDocumentSalesUseCase($this->documentTypeRepository);
        $documentTypes = $documentTypeUseCase->execute();

        return DocumentTypeResource::collection($documentTypes)->resolve();
    }

    public function indexPurchases(): array
    {
        $documentTypeUseCase = new FindAllForPurchasesUseCases($this->documentTypeRepository);
        $documentTypes = $documentTypeUseCase->execute();

        return DocumentTypeResource::collection($documentTypes)->resolve();
    }
    public function indexPettyCashInfinite(Request $request): JsonResponse
    {
        $cursor = $request->query('cursor');
        $paginator = $this->documentTypeRepository->findAllForPettyCashInfinite();

        $data = collect($paginator->items())->map(function ($doc) {
            return [
                'id' => $doc->id,
                'status' => ($doc->status) == 1 ? 'Activo' : 'Inactivo',
                'description' => $doc->description,
            ];
        })->all();

        return new JsonResponse([
            'data' => $data,
            'next_cursor' => $paginator->nextCursor()?->encode(),
            'prev_cursor' => $paginator->previousCursor()?->encode(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'per_page' => $paginator->perPage()
        ]);
    }

    public function indexEntryGuides(): array
    {
        $documentTypeUseCase = new FindAllForEntryGuidesUseCase($this->documentTypeRepository);
        $documentTypes = $documentTypeUseCase->execute();

        return DocumentTypeResource::collection($documentTypes)->resolve();
    }
}

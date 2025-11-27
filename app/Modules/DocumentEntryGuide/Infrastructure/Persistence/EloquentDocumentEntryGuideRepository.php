<?php
namespace App\Modules\DocumentEntryGuide\Infrastructure\Persistence;

use App\Modules\DocumentEntryGuide\Domain\Entities\DocumentEntryGuide;
use App\Modules\DocumentEntryGuide\Domain\Interface\DocumentEntryGuideRepositoryInterface;
use App\Modules\DocumentEntryGuide\Infrastructure\Models\EloquentDocumentEntryGuide;

class EloquentDocumentEntryGuideRepository implements DocumentEntryGuideRepositoryInterface
{
    public function create(DocumentEntryGuide $documentEntryGuide)
    {
        $eloquentCreate = EloquentDocumentEntryGuide::create([
            'entry_guide_id' => $documentEntryGuide->getEntryGuideId(),
            'guide_serie_supplier' => $documentEntryGuide->getGuideSerieSupplier(),
            'guide_correlative_supplier' => $documentEntryGuide->getGuideCorrelativeSupplier(),
            'invoice_serie_supplier' => $documentEntryGuide->getInvoiceSerieSupplier(),
            'invoice_correlative_supplier' => $documentEntryGuide->getInvoiceCorrelativeSupplier(),
        ]);

        return new DocumentEntryGuide(
            id:$eloquentCreate->id,
            entry_guide_id:$eloquentCreate->entry_guide_id,
            guide_serie_supplier:$eloquentCreate->guide_serie_supplier,
            guide_correlative_supplier:$eloquentCreate->guide_correlative_supplier,
            invoice_serie_supplier:$eloquentCreate->invoice_serie_supplier,
            invoice_correlative_supplier:$eloquentCreate->invoice_correlative_supplier,
        );
    }

    public function findById($id): ?DocumentEntryGuide
    {
       $eloquentEntryGuide = EloquentDocumentEntryGuide::find($id);

       return new DocumentEntryGuide(
         id:$eloquentEntryGuide->id,
         entry_guide_id:$eloquentEntryGuide->entry_guide_id,
         guide_serie_supplier:$eloquentEntryGuide->guide_serie_supplier,
         guide_correlative_supplier:$eloquentEntryGuide->guide_correlative_supplier,
         invoice_serie_supplier:$eloquentEntryGuide->invoice_serie_supplier,
         invoice_correlative_supplier:$eloquentEntryGuide->invoice_correlative_supplier,
       );
    }

    public function findAll():array
    {
        $eloquentEntryGuides = EloquentDocumentEntryGuide::all();

        return $eloquentEntryGuides->map(function ($eloquentEntryGuide) {
            return new DocumentEntryGuide(
                id:$eloquentEntryGuide->id,
                entry_guide_id:$eloquentEntryGuide->entry_guide_id,
                guide_serie_supplier:$eloquentEntryGuide->guide_serie_supplier,
                guide_correlative_supplier:$eloquentEntryGuide->guide_correlative_supplier,
                invoice_serie_supplier:$eloquentEntryGuide->invoice_serie_supplier,
                invoice_correlative_supplier:$eloquentEntryGuide->invoice_correlative_supplier,
            );
        })->toArray();
    }
}
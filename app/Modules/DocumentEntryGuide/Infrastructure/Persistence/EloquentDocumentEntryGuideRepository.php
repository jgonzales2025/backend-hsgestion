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
            'reference_document_id' => $documentEntryGuide->getReferenceDocument()->getId(),
            'reference_serie' => $documentEntryGuide->getReferenceSerie(),
            'reference_correlative' => $documentEntryGuide->getReferenceCorrelative(),
        ]);

        return new DocumentEntryGuide(
            id: $eloquentCreate->id,
            entry_guide_id: $eloquentCreate->entry_guide_id,
            reference_document: $eloquentCreate->referenceDocument->toDomain($eloquentCreate->referenceDocument),
            reference_serie: $eloquentCreate->reference_serie,
            reference_correlative: $eloquentCreate->reference_correlative,
        );
    }

    public function findById($id): array
    {
        $eloquentEntryGuide = EloquentDocumentEntryGuide::where('entry_guide_id', $id)->get();

        return $eloquentEntryGuide->map(function ($eloquentEntryGuide) {
            return new DocumentEntryGuide(
                id: $eloquentEntryGuide->id,
                entry_guide_id: $eloquentEntryGuide->entry_guide_id,
                reference_document: $eloquentEntryGuide->referenceDocument->toDomain($eloquentEntryGuide->referenceDocument),
                reference_serie: $eloquentEntryGuide->reference_serie,
                reference_correlative: $eloquentEntryGuide->reference_correlative,
            );
        })->toArray();
    }

    public function findAll(): array
    {
        $eloquentEntryGuides = EloquentDocumentEntryGuide::with('referenceDocument')->get();

        return $eloquentEntryGuides->map(function ($eloquentEntryGuide) {
            return new DocumentEntryGuide(
                id: $eloquentEntryGuide->id,
                entry_guide_id: $eloquentEntryGuide->entry_guide_id,
                reference_document: $eloquentEntryGuide->referenceDocument->toDomain($eloquentEntryGuide->referenceDocument),
                reference_serie: $eloquentEntryGuide->reference_serie,
                reference_correlative: $eloquentEntryGuide->reference_correlative,
            );
        })->toArray();
    }

    public function findByIdObj(int $id): ?DocumentEntryGuide
    {
        $eloquentEntryGuide = EloquentDocumentEntryGuide::where('entry_guide_id', $id)->first();

        if (!$eloquentEntryGuide) {
            return null;
        }

        return new DocumentEntryGuide(
            id: $eloquentEntryGuide->id,
            entry_guide_id: $eloquentEntryGuide->entry_guide_id,
            reference_document: $eloquentEntryGuide->referenceDocument->toDomain($eloquentEntryGuide->referenceDocument),
            reference_serie: $eloquentEntryGuide->reference_serie,
            reference_correlative: $eloquentEntryGuide->reference_correlative,
        );
    }
}

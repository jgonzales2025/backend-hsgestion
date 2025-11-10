<?php

namespace App\Modules\EntryGuides\Infrastructure\Persistence;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryGuides\Infrastructure\Models\EloquentEntryGuide;

class EloquentEntryGuideRepository implements EntryGuideRepositoryInterface
{

public function findAll(): array
{
    // Cargar relaciones para evitar N+1
    $eloquentAll = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason'])->get();

    return $eloquentAll->map(function ($entryGuide) {
        return new EntryGuide(
            id: $entryGuide->id,
            cia: $entryGuide->company?->toDomain($entryGuide->company),
            branch: $entryGuide->branch?->toDomain($entryGuide->branch),
            serie: $entryGuide->serie,
            correlative: $entryGuide->correlative,
            date: $entryGuide->date,
            customer: $entryGuide->customer?->toDomain($entryGuide->customer),
            guide_serie_supplier: $entryGuide->guide_serie_supplier,
            guide_correlative_supplier: $entryGuide->guide_correlative_supplier,
            invoice_serie_supplier: $entryGuide->invoice_serie_supplier,
            invoice_correlative_supplier: $entryGuide->invoice_correlative_supplier,
            observations: $entryGuide->observations,
            ingressReason: $entryGuide->ingressReason?->toDomain($entryGuide->ingressReason),
            reference_serie: $entryGuide->reference_serie,
            reference_correlative: $entryGuide->reference_correlative,
            status: $entryGuide->status,
        );
    })->toArray();
}

    public function save(EntryGuide $entryGuide): ?EntryGuide
    {

    }
    public function findById(int $id): ?EntryGuide
    {
         $eloquentEntryGuide = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason'])->find($id);

        if (!$eloquentEntryGuide) {
            return null;
        }
        return new EntryGuide(
            id: $eloquentEntryGuide->id,
            cia: $eloquentEntryGuide->company?->toDomain($eloquentEntryGuide->company),
            branch: $eloquentEntryGuide->branch?->toDomain($eloquentEntryGuide->branch),
            serie: $eloquentEntryGuide->serie,
            correlative: $eloquentEntryGuide->correlative,
            date: $eloquentEntryGuide->date,
            customer: $eloquentEntryGuide->customer?->toDomain($eloquentEntryGuide->customer),
            guide_serie_supplier: $eloquentEntryGuide->guide_serie_supplier,
            guide_correlative_supplier: $eloquentEntryGuide->guide_correlative_supplier,
            invoice_serie_supplier: $eloquentEntryGuide->invoice_serie_supplier,
            invoice_correlative_supplier: $eloquentEntryGuide->invoice_correlative_supplier,
            observations: $eloquentEntryGuide->observations,
            ingressReason: $eloquentEntryGuide->ingressReason?->toDomain($eloquentEntryGuide->ingressReason),
            reference_serie: $eloquentEntryGuide->reference_serie,
            reference_correlative: $eloquentEntryGuide->reference_correlative,
            status: $eloquentEntryGuide->status,
        );
    
    }



}
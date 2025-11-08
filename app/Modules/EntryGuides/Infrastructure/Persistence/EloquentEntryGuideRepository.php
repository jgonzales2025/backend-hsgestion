<?php

namespace App\Modules\EntryGuides\Infrastructure\Persistence;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;

class EloquentEntryGuideRepository implements EntryGuideRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentAll = EloquentArticle::all();
        return $eloquentAll->map(function ($entryGuide) {
            return new EntryGuide(
                id: 1,
                cia: $entryGuide->cia,
                branch: $entryGuide->branch,
                serie: $entryGuide->serie,
                correlativo: $entryGuide->correlativo,
                date: $entryGuide->date,
                customer: $entryGuide->customer,
                guide_serie_supplier: $entryGuide->guide_serie_supplier,
                guide_correlative_supplier: $entryGuide->guide_correlative_supplier,
                invoice_serie_supplier: $entryGuide->invoice_serie_supplier,
                invoice_correlative_supplier: $entryGuide->invoice_correlative_supplier,
                observations: $entryGuide->observations,
                ingressReason: $entryGuide->ingressReason,
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

    }



}
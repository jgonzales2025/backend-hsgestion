<?php

namespace App\Modules\EntryGuides\Infrastructure\Persistence;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryGuides\Infrastructure\Models\EloquentEntryGuide;
use PhpParser\Node\NullableType;

class EloquentEntryGuideRepository implements EntryGuideRepositoryInterface
{

    public function findAll(?string $serie, ?string $correlativo): array|EntryGuide
    {
        $query = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason']);


        if (!empty($serie)) {
            $query->where(function ($q) use ($serie) {
                $q->where('customer_id', $serie);
                //   ->orWhere('correlative', $seriecorrel);
            });
        }

        if (!empty($correlativo)) {
            $query->where(function ($q) use ($correlativo) {
                $q->where('correlative', $correlativo);
            });
        }

        $eloquentAll = $query->orderByDesc('id')->get();

        return $eloquentAll->map(function ($entryGuide) {
            return new EntryGuide(
                id: $entryGuide->id,
                cia: $entryGuide->company?->toDomain($entryGuide->company),
                branch: $entryGuide->branch?->toDomain($entryGuide->branch),
                serie: $entryGuide->serie,
                correlative: $entryGuide->correlative,
                date: $entryGuide->date,
                customer: $entryGuide->customer?->toDomain($entryGuide->customer),
                observations: $entryGuide->observations,
                ingressReason: $entryGuide->ingressReason?->toDomain($entryGuide->ingressReason),
                reference_po_serie: $entryGuide->reference_po_serie,
                reference_po_correlative: $entryGuide->reference_po_correlative,
                status: 1,
            );
        })->toArray();
    }
    public function findByCorrelative(?string $correlativo): ?EntryGuide
    {

        $query = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason']);
        $query->where('correlative', $correlativo);
        $entryGuide = $query->first();
        if (!$entryGuide) {
            return null;
        }
        return new EntryGuide(
            id: $entryGuide->id,
            cia: $entryGuide->company?->toDomain($entryGuide->company),
            branch: $entryGuide->branch?->toDomain($entryGuide->branch),
            serie: $entryGuide->serie,
            correlative: $entryGuide->correlative,
            date: $entryGuide->date,
            customer: $entryGuide->customer?->toDomain($entryGuide->customer),
            observations: $entryGuide->observations,
            ingressReason: $entryGuide->ingressReason?->toDomain($entryGuide->ingressReason),
            reference_po_serie: $entryGuide->reference_serie,
            reference_po_correlative: $entryGuide->reference_correlative,
            status: $entryGuide->status
        );
    }
    public function save(EntryGuide $entryGuide): ?EntryGuide
    {
        $eloquentEntryGuide = EloquentEntryGuide::create([
            'cia_id' => $entryGuide->getCompany()->getId(),
            'branch_id' => $entryGuide->getBranch()->getId(),
            'serie' => $entryGuide->getSerie(),
            'correlative' => $entryGuide->getCorrelativo(),
            'date' => $entryGuide->getDate(),
            'customer_id' => $entryGuide->getCustomer()->getId(),
            'observations' => $entryGuide->getObservations(),
            'ingress_reason_id' => $entryGuide->getIngressReason()->getId(),
            'reference_po_serie' => $entryGuide->getReferenceCorrelative(),
            'reference_po_correlative' => $entryGuide->getReferenceCorrelative(),
            'status' => 1,
        ]);
        return new EntryGuide(
            id: $eloquentEntryGuide->id,
            cia: $eloquentEntryGuide->company?->toDomain($eloquentEntryGuide->company),
            branch: $eloquentEntryGuide->branch?->toDomain($eloquentEntryGuide->branch),
            serie: $eloquentEntryGuide->serie,
            correlative: $eloquentEntryGuide->correlative,
            date: $eloquentEntryGuide->date,
            customer: $eloquentEntryGuide->customer?->toDomain($eloquentEntryGuide->customer),
            observations: $eloquentEntryGuide->observations,
            ingressReason: $eloquentEntryGuide->ingressReason?->toDomain($eloquentEntryGuide->ingressReason),
            reference_po_serie: $eloquentEntryGuide->reference_serie,
            reference_po_correlative: $eloquentEntryGuide->reference_correlative,
            status: $eloquentEntryGuide->status
        );
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
            observations: $eloquentEntryGuide->observations,
            ingressReason: $eloquentEntryGuide->ingressReason?->toDomain($eloquentEntryGuide->ingressReason),
            reference_po_serie: $eloquentEntryGuide->reference_serie,
            reference_po_correlative: $eloquentEntryGuide->reference_correlative,
            status: $eloquentEntryGuide->status,
        );
    }
    public function update(EntryGuide $entryGuide): EntryGuide|null
    {
        $eloquentEntryGuide = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason'])->find($entryGuide->getId());

        if (!$eloquentEntryGuide) {
            return null;
        }

        $eloquentEntryGuide->update([
            'cia_id' => $entryGuide->getCompany()->getId(),
            'branch_id' => $entryGuide->getBranch()->getId(),
            'date' => $entryGuide->getDate(),
            'customer_id' => $entryGuide->getCustomer()->getId(),
            'observations' => $entryGuide->getObservations(),
            'ingress_reason_id' => $entryGuide->getIngressReason()->getId(),
            'reference_po_serie' => $entryGuide->getReferenceCorrelative(),
            'reference_po_correlative' => $entryGuide->getReferenceCorrelative(),
            'status' => 1,
        ]);

        return new EntryGuide(
            id: $eloquentEntryGuide->id,
            cia: $eloquentEntryGuide->company?->toDomain($eloquentEntryGuide->company),
            branch: $eloquentEntryGuide->branch?->toDomain($eloquentEntryGuide->branch),
            serie: $eloquentEntryGuide->serie,
            correlative: $eloquentEntryGuide->correlative,
            date: $eloquentEntryGuide->date,
            customer: $eloquentEntryGuide->customer?->toDomain($eloquentEntryGuide->customer),
            observations: $eloquentEntryGuide->observations,
            ingressReason: $eloquentEntryGuide->ingressReason?->toDomain($eloquentEntryGuide->ingressReason),
            reference_po_serie: $eloquentEntryGuide->reference_serie,
            reference_po_correlative: $eloquentEntryGuide->reference_correlative,
            status: $eloquentEntryGuide->status,
        );
    }

    public function getLastDocumentNumber(string $serie): ?string
    {
        $entryGuide = EloquentEntryGuide::where('serie', $serie)
            ->orderBy('correlative', 'desc')
            ->first();

        return $entryGuide?->correlative;
    }

    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $eloquentAll = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason'])
            ->whereIn('id', $ids)
            ->orderByDesc('id')
            ->get();

        if ($eloquentAll->isEmpty()) {
            return [];
        }

        return $eloquentAll->map(function ($entryGuide) {
            return new EntryGuide(
                id: $entryGuide->id,
                cia: $entryGuide->company?->toDomain($entryGuide->company),
                branch: $entryGuide->branch?->toDomain($entryGuide->branch),
                serie: $entryGuide->serie,
                correlative: $entryGuide->correlative,
                date: $entryGuide->date,
                customer: $entryGuide->customer?->toDomain($entryGuide->customer),
                observations: $entryGuide->observations,
                ingressReason: $entryGuide->ingressReason?->toDomain($entryGuide->ingressReason),
                reference_po_serie: $entryGuide->reference_po_serie,
                reference_po_correlative: $entryGuide->reference_po_correlative,
                status: 1,
            );
        })->toArray();
    }

    public function allBelongToSameCustomer(array $ids): bool
    {
        if (empty($ids)) {
            return false;
        }

        $distinctCustomers = EloquentEntryGuide::whereIn('id', $ids)
            ->select('customer_id')
            ->distinct()
            ->pluck('customer_id');

        return $distinctCustomers->count() === 1;
    }
}

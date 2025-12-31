<?php

namespace App\Modules\EntryGuides\Infrastructure\Persistence;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryGuides\Infrastructure\Models\EloquentEntryGuide;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\NullableType;

class EloquentEntryGuideRepository implements EntryGuideRepositoryInterface
{

    public function findAll(?string $description, ?int $status, ?int $reference_document_id, ?string $reference_serie, ?string $reference_correlative, ?int $supplier_id): LengthAwarePaginator
    {
        $query = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason', 'documentEntryGuides', 'currency'])
            ->when(
                $description,
                fn($query) => $query->whereHas(
                    'customer',
                    fn($q) =>

                    $q->where('name', 'like', "%{$description}%")
                        ->orWhere('lastname', 'like', "%{$description}%")
                        ->orWhere('second_lastname', 'like', "%{$description}%")
                        ->orWhere('company_name', 'like', "%{$description}%")
                )
            )
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->when(
                $reference_document_id !== null,
                fn($query) =>
                $query->whereHas(
                    'documentEntryGuides',
                    fn($q) =>
                    $q->where('reference_document_id', $reference_document_id)
                )
            )
            ->when(
                $reference_serie !== null,
                fn($query) =>
                $query->whereHas(
                    'documentEntryGuides',
                    fn($q) =>
                    $q->where('reference_serie', $reference_serie)
                )
            )
            ->when(
                $reference_correlative !== null,
                fn($query) =>
                $query->whereHas(
                    'documentEntryGuides',
                    fn($q) =>
                    $q->where('reference_correlative', $reference_correlative)
                )
            )
            ->when(
                $supplier_id !== null,
                fn($query) =>
                $query->where('customer_id', $supplier_id)
            );
        $paginator = $query->orderByDesc('id')->paginate(10);

        $paginator->getCollection()->transform(function ($entryGuide) {
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
                status: $entryGuide->status,
                subtotal: $entryGuide->subtotal,
                total_descuento: $entryGuide->total_descuento,
                total: $entryGuide->total,
                update_price: (bool) $entryGuide->update_price,
                entry_igv: $entryGuide->entry_igv,
                currency: $entryGuide->currency?->toDomain($entryGuide->currency),
                includ_igv: $entryGuide->includ_igv,
                reference_document_id: $entryGuide->reference_document_id,
            );
        });

        return $paginator;
    }


    public function findByCorrelative(?string $correlativo): ?EntryGuide
    {

        $query = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason', 'documentEntryGuides']);
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
            status: $entryGuide->status,
            subtotal: $entryGuide->subtotal,
            total_descuento: $entryGuide->total_descuento,
            total: $entryGuide->total,
            update_price: (bool) $entryGuide->update_price,
            entry_igv: $entryGuide->entry_igv,
            currency: $entryGuide->currency_id,
            includ_igv: $entryGuide->includ_igv,
            reference_document_id: $entryGuide->reference_document_id,
        );
    }
    public function findBySerieAndCorrelative(string $serie, string $correlative): ?EntryGuide
    {
        $entryGuide = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason', 'currency'])
            ->where('reference_serie', $serie)
            ->where('reference_correlative', $correlative)
            ->first();

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
            status: $entryGuide->status,
            subtotal: $entryGuide->subtotal,
            total_descuento: $entryGuide->total_descuento,
            total: $entryGuide->total,
            update_price: (bool) $entryGuide->update_price,
            entry_igv: $entryGuide->entry_igv,
            currency: $entryGuide->currency?->toDomain($entryGuide->currency),
            includ_igv: $entryGuide->includ_igv,
            reference_document_id: $entryGuide->reference_document_id,
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
            'reference_serie' => $entryGuide->getReferenceSerie(),
            'reference_correlative' => $entryGuide->getReferenceCorrelative(),
            'subtotal' => $entryGuide->getSubtotal(),
            'total_descuento' => $entryGuide->getTotalDescuento(),
            'total' => $entryGuide->getTotal(),
            'update_price' => $entryGuide->getUpdatePrice(),
            'entry_igv' => $entryGuide->getEntryIgv(),
            'currency_id' => $entryGuide->getCurrency()->getId(),
            'includ_igv' => $entryGuide->getIncludIgv(),
            'reference_document_id' => $entryGuide->getReferenceDocument(),
        ]);

        // // ⚠️ SIN DB::transaction()
        // DB::statement('CALL sp_update_price_article_by_entry_guide(?, ?)', [
        //     $entryGuide->getCompany()->getId(),
        //     $eloquentEntryGuide->id, 
        // ]);

        $eloquentEntryGuide->refresh();
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
            subtotal: $eloquentEntryGuide->subtotal,
            total_descuento: $eloquentEntryGuide->total_descuento,
            total: $eloquentEntryGuide->total,
            update_price: (bool) $eloquentEntryGuide->update_price,
            entry_igv: $eloquentEntryGuide->entry_igv,
            currency: $eloquentEntryGuide->currency?->toDomain($eloquentEntryGuide->currency),
            includ_igv: $eloquentEntryGuide->includ_igv,
            reference_document_id: $eloquentEntryGuide->reference_document_id,
        );
    }

    public function findById(int $id): ?EntryGuide
    {
        $eloquentEntryGuide = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason', 'documentEntryGuides', 'currency'])->find($id);

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
            subtotal: $eloquentEntryGuide->subtotal,
            total_descuento: $eloquentEntryGuide->total_descuento,
            total: $eloquentEntryGuide->total,
            update_price: (bool) $eloquentEntryGuide->update_price,
            entry_igv: $eloquentEntryGuide->entry_igv,
            currency: $eloquentEntryGuide->currency?->toDomain($eloquentEntryGuide->currency),
            includ_igv: $eloquentEntryGuide->includ_igv,
            reference_document_id: $eloquentEntryGuide->reference_document_id,
        );
    }
    public function update(EntryGuide $entryGuide): EntryGuide|null
    {
        $eloquentEntryGuide = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason', 'documentEntryGuides'])->find($entryGuide->getId());

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
            'reference_serie' => $entryGuide->getReferenceSerie(),
            'reference_correlative' => $entryGuide->getReferenceCorrelative(),
            'status' => 1,
            'subtotal' => $entryGuide->getSubtotal(),
            'total_descuento' => $entryGuide->getTotalDescuento(),
            'total' => $entryGuide->getTotal(),
            'update_price' => $entryGuide->getUpdatePrice(),
            'includ_igv' => $entryGuide->getIncludIgv(),
            'entry_igv' => $entryGuide->getEntryIgv(),
            'currency_id' => $entryGuide->getCurrency()->getId(),
            'reference_document_id' => $entryGuide->getReferenceDocument(),
        ]);

        // DB::statement('CALL sp_update_price_article_by_entry_guide(?,?)',[
        //     $entryGuide->getCompany()->getId(),
        //     $entryGuide->getId(),
        // ]);

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
            subtotal: $eloquentEntryGuide->subtotal,
            total_descuento: $eloquentEntryGuide->total_descuento,
            total: $eloquentEntryGuide->total,
            update_price: (bool) $eloquentEntryGuide->update_price,
            entry_igv: $eloquentEntryGuide->entry_igv,
            currency: $eloquentEntryGuide->currency?->toDomain($eloquentEntryGuide->currency),
            includ_igv: $eloquentEntryGuide->includ_igv,
            reference_document_id: $eloquentEntryGuide->reference_document_id,
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

        $eloquentAll = EloquentEntryGuide::with(['branch', 'customer', 'ingressReason', 'documentEntryGuides', 'currency'])
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
                reference_po_serie: $entryGuide->reference_serie,
                reference_po_correlative: $entryGuide->reference_correlative,
                status: 1,
                subtotal: $entryGuide->subtotal,
                total_descuento: $entryGuide->total_descuento,
                total: $entryGuide->total,
                update_price: (bool) $entryGuide->update_price,
                entry_igv: $entryGuide->entry_igv,
                currency: $entryGuide->currency?->toDomain($entryGuide->currency),
                includ_igv: $entryGuide->includ_igv,
                reference_document_id: $entryGuide->reference_document_id,
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

    public function updateStatus(int $id, int $status): void
    {
        EloquentEntryGuide::where('id', $id)->update(['status' => $status]);
    }
}

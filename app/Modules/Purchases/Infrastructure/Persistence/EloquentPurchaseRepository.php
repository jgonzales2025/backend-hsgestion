<?php

namespace App\Modules\Purchases\Infrastructure\Persistence;

use App\Modules\DetailPurchaseGuides\Infrastructure\Models\EloquentDetailPurchaseGuide;
use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Models\EloquentPurchase;
use App\Modules\ShoppingIncomeGuide\Infrastructure\Models\EloquentShoppingIncomeGuide;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentPurchaseRepository implements PurchaseRepositoryInterface
{
    public function getLastDocumentNumber(int $company_id, int $branch_id, string $serie): ?string
    {
        $purchase = EloquentPurchase::all()
            ->where('company_id', $company_id)
            ->where('branch_id', $branch_id)
            ->where('serie', $serie)
            ->sortByDesc('correlative')
            ->first();

        return $purchase?->correlative;
    }

    public function findAll(?string $description, $num_doc, $id_proveedr)
    {
        $eloquentpurchase = EloquentPurchase::with([
            'paymentType',
            'branches',
            'customers',
            'currencyType',
            'documentType',
            'detComprasGuiaIngreso',
            'shoppingIncomeGuide'
        ])

            ->when($description, function ($query) use ($description) {
                $query->where(function ($q) use ($description) {
                    $q->whereHas('customers', function ($c) use ($description) {
                        $c->where('name', 'like', "%{$description}%")
                            ->orWhere('lastname', 'like', "%{$description}%")
                            ->orWhere('second_lastname', 'like', "%{$description}%")
                            ->orWhere('company_name', 'like', "%{$description}%")
                            ->orWhere('reference_serie', 'like', "%{$description}%")
                            ->orWhere('reference_correlative', 'like', "%{$description}%")
                            ->orWhere('serie', 'like', "%{$description}%")
                            ->orWhere('correlative', 'like', "%{$description}%");
                    })
                        ->orWhereHas('paymentType', function ($p) use ($description) {
                            $p->where('name', 'like', "%{$description}%");
                        });
                });
            })
            ->when(
                $num_doc,
                fn($query) =>
                $query->where('document_type_id', $num_doc)
            )
            ->when(
                $id_proveedr,
                fn($query) =>
                $query->where('supplier_id', $id_proveedr)
            )
            ->orderByDesc('id')
            ->paginate(10);

        $eloquentpurchase->getCollection()->transform(fn($purchase) => $purchase->toDomain());

        return $eloquentpurchase;
    }

    public function findById(int $id): ?Purchase
    {
        return $this->findWithRelations($id);
    }

    public function save(Purchase $purchase): ?Purchase
    {
        return DB::transaction(function () use ($purchase) {
            $eloquentpurchase = EloquentPurchase::create([
                'company_id' => $purchase->getCompanyId(),
                'branch_id' => $purchase->getBranch()->getId(),
                'supplier_id' => $purchase->getSupplier()->getId(),
                'serie' => $purchase->getSerie(),
                'correlative' => $purchase->getCorrelative(),
                'exchange_type' => $purchase->getExchangeType(),
                'payment_type_id' => $purchase->getPaymentType()->getId(),
                'currency' => $purchase->getCurrency()->getId(),
                'date' => $purchase->getDate(),
                'date_ven' => $purchase->getDateVen(),
                'days' => $purchase->getDays(),
                'observation' => $purchase->getObservation(),
                'detraccion' => $purchase->getDetraccion(),
                'fech_detraccion' => $purchase->getFechDetraccion(),
                'amount_detraccion' => $purchase->getAmountDetraccion(),
                'is_detracion' => $purchase->getIsDetracion(),
                'subtotal' => $purchase->getSubtotal(),
                'total_desc' => $purchase->getTotalDesc(),
                'inafecto' => $purchase->getInafecto(),
                'igv' => $purchase->getIgv(),
                'total' => $purchase->getTotal(),
                'is_igv' => $purchase->getIsIgv(),
                'document_type_id' => $purchase->getTypeDocumentId()->getId(),
                'reference_serie' => $purchase->getReferenceSerie(),
                'reference_correlative' => $purchase->getReferenceCorrelative(),
                'saldo' => $purchase->getTotal(),
            ]);

            foreach ($purchase->getDetComprasGuiaIngreso() as $det) {
                EloquentDetailPurchaseGuide::create([
                    'purchase_id'     => $eloquentpurchase->id,
                    'article_id'      => $det->article_id,
                    'description'     => $det->description,
                    'cantidad'        => $det->cantidad,
                    'precio_costo'    => $det->precio_costo,
                    'descuento'       => $det->descuento,
                    'sub_total'       => $det->sub_total,
                    'total'           => $det->total,
                    'cantidad_update' => $det->cantidad,
                    'process_status'  => $det->process_status,
                ]);

                DB::statement('CALL descontar_saldo_fifo(?,?,?,?,?,?,?)', [
                    $purchase->getCompanyId(),                  // cia
                    $purchase->getSupplier()->getId(),          // cliente
                    $det->article_id,                           // artículo correcto
                    $purchase->getTypeDocumentId()->getId(),    // tipo documento
                    $purchase->getReferenceSerie(),             // serie
                    $purchase->getReferenceCorrelative(),       // correlativo
                    $det->cantidad                              // cantidad
                ]);
            }

            foreach ($purchase->getShoppingIncomeGuide() as $shopping_Income_Guide) {
                EloquentShoppingIncomeGuide::create([
                    'purchase_id'   => $eloquentpurchase->id,
                    'entry_guide_id' => $shopping_Income_Guide->entry_guide_id,
                ]);
            }

            return $this->findWithRelations($eloquentpurchase->id);
        });
    }

    public function update(Purchase $purchase): ?Purchase
    {
        return DB::transaction(function () use ($purchase) {
            $eloquentpurchase = EloquentPurchase::find($purchase->getId());

            if (!$eloquentpurchase) {
                return null;
            }

            $eloquentpurchase->update([
                'branch_id' => $purchase->getBranch()->getId(),
                'supplier_id' => $purchase->getSupplier()->getId(),
                'serie' => $purchase->getSerie(),
                'correlative' => $purchase->getCorrelative(),
                'exchange_type' => $purchase->getExchangeType(),
                'payment_type_id' => $purchase->getPaymentType()->getId(),
                'currency' => $purchase->getCurrency()->getId(),
                'date' => $purchase->getDate(),
                'date_ven' => $purchase->getDateVen(),
                'days' => $purchase->getDays(),
                'observation' => $purchase->getObservation(),
                'detraccion' => $purchase->getDetraccion(),
                'fech_detraccion' => $purchase->getFechDetraccion(),
                'amount_detraccion' => $purchase->getAmountDetraccion(),
                'is_detracion' => $purchase->getIsDetracion(),
                'subtotal' => $purchase->getSubtotal(),
                'total_desc' => $purchase->getTotalDesc(),
                'inafecto' => $purchase->getInafecto(),
                'igv' => $purchase->getIgv(),
                'total' => $purchase->getTotal(),
                'is_igv' => $purchase->getIsIgv(),
                'document_type_id' => $purchase->getTypeDocumentId()->getId(),
                'reference_serie' => $purchase->getReferenceSerie(),
                'reference_correlative' => $purchase->getReferenceCorrelative(),
                'company_id' => $purchase->getCompanyId(),
                'saldo' => $purchase->getTotal(),
            ]);

            EloquentDetailPurchaseGuide::where('purchase_id', $eloquentpurchase->id)->delete();
            foreach ($purchase->getDetComprasGuiaIngreso() as $det) {
                EloquentDetailPurchaseGuide::create([
                    'purchase_id'     => $eloquentpurchase->id,
                    'article_id'      => $det->article_id,
                    'description'     => $det->description,
                    'cantidad'        => $det->cantidad,
                    'precio_costo'    => $det->precio_costo,
                    'descuento'       => $det->descuento,
                    'sub_total'       => $det->sub_total,
                    'total'           => $det->total,
                    'cantidad_update' => $det->cantidad,
                    'process_status'  => $det->process_status,
                ]);
            }

            EloquentShoppingIncomeGuide::where('purchase_id', $eloquentpurchase->id)->delete();
            foreach ($purchase->getShoppingIncomeGuide() as $shopping_Income_Guide) {
                EloquentShoppingIncomeGuide::create([
                    'purchase_id'   => $eloquentpurchase->id,
                    'entry_guide_id' => $shopping_Income_Guide->entry_guide_id,
                ]);
            }

            return $this->findWithRelations($eloquentpurchase->id);
        });
    }
    public function findBySerieAndCorrelative(string $serie, string $correlative): ?Purchase
    {
        $eloquentpurchase = EloquentPurchase::where('reference_serie', $serie)
            ->where('reference_correlative', $correlative)
            ->first();

        if (!$eloquentpurchase) {
            return null;
        }

        return  $this->findWithRelations($eloquentpurchase->id);
    }

    public function findAllExcel(?string $description, $num_doc, $id_proveedr): Collection
    {
        $companyId = request()->get('company_id');

        $purchases = EloquentPurchase::with([
            'paymentType',
            'branches',
            'customers',
            'currencyType',
            'documentType'
        ])
            ->where('company_id', $companyId)
            ->when($description, function ($query) use ($description) {
                $query->where(function ($q) use ($description) {
                    $q->whereHas('customers', function ($c) use ($description) {
                        $c->where('name', 'like', "%{$description}%")
                            ->orWhere('lastname', 'like', "%{$description}%")
                            ->orWhere('second_lastname', 'like', "%{$description}%")
                            ->orWhere('company_name', 'like', "%{$description}%")
                            ->orWhere('reference_serie', 'like', "%{$description}%")
                            ->orWhere('reference_correlative', 'like', "%{$description}%")
                            ->orWhere('serie', 'like', "%{$description}%")
                            ->orWhere('correlative', 'like', "%{$description}%");
                    })
                        ->orWhereHas('paymentType', function ($p) use ($description) {
                            $p->where('name', 'like', "%{$description}%");
                        });
                });
            })
            ->when($num_doc, fn($query) => $query->where('document_type_id', $num_doc))
            ->when($id_proveedr, fn($query) => $query->where('supplier_id', $id_proveedr))
            ->orderByDesc('id')
            ->get();

        return $purchases->map(function ($purchase) {
            return $purchase->toDomain();
        });
    }

    public function dowloadPdf(int $id): ?Purchase
    {
        $purchase = $this->findWithRelations($id);
        if (!$purchase) {
            return null;
        }
        return $purchase;
    }

    private function findWithRelations(int $id): ?Purchase
    {
        $model = EloquentPurchase::with([
            'paymentType',
            'branches',
            'customers',
            'currencyType',
            'documentType',
            'detComprasGuiaIngreso',
            'shoppingIncomeGuide'
        ])->find($id);

        return $model?->toDomain();
    }
}

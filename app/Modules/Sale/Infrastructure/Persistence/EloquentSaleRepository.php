<?php

namespace App\Modules\Sale\Infrastructure\Persistence;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Entities\SaleCreditNote;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentSaleRepository implements SaleRepositoryInterface
{
    public function findAll(int $companyId): array
    {
        $eloquentSale = EloquentSale::all()->where('company_id', $companyId)->sortByDesc('created_at');
        if ($eloquentSale->isEmpty()) {
            return [];
        }

        return $eloquentSale->map(fn($sale) => $this->mapToDomain($sale))->toArray();
    }

    public function save(Sale $sale): ?Sale
    {
        $eloquentSale = EloquentSale::create($this->mapToArray($sale));

        if (!is_null($eloquentSale->reference_document_type_id)) {
            $cot = EloquentSale::where('id', $eloquentSale->reference_document_type_id)->first();

            if ($cot) {
                $cot->status = 0;
                $cot->save();
            }
        }

        return $this->buildDomainSale($eloquentSale, $sale);
    }

    public function saveCreditNote(SaleCreditNote $saleCreditNote): ?SaleCreditNote
    {
        $eloquentSaleCreditNote = EloquentSale::create($this->mapToArrayCreditNote($saleCreditNote));
        return $this->buildDomainSaleCreditNote($eloquentSaleCreditNote, $saleCreditNote);
    }

    public function getLastDocumentNumber(string $serie): ?string
    {
        $sale = EloquentSale::where('serie', $serie)
            ->orderBy('document_number', 'desc')
            ->first();

        return $sale?->document_number;
    }

    public function findById(int $id): ?Sale
    {
        $eloquentSale = EloquentSale::find($id);

        if (!$eloquentSale) {
            return null;
        }

        return $this->mapToDomain($eloquentSale);
    }

    public function update(Sale $sale): ?Sale
    {
        $eloquentSale = EloquentSale::find($sale->getId());

        $eloquentSale->update($this->mapToArray($sale));

        $this->updateSaleBalance($eloquentSale);
        $eloquentSale = $eloquentSale->fresh();

        return $this->buildDomainSale($eloquentSale, $sale);
    }

    public function findByDocumentSale(int $documentTypeId, string $serie, string $correlative): ?Sale
    {
        $eloquentSale = EloquentSale::where('document_type_id', $documentTypeId)
            ->where('serie', $serie)
            ->where('document_number', $correlative)
            ->first();

        if (!$eloquentSale) {
            return null;
        }

        return $this->mapToDomain($eloquentSale);
    }

    public function findAllProformas(): array
    {
        $eloquentSalesProformas = EloquentSale::where('document_type_id', 16)->orderBy('created_at', 'desc')->get();

        if ($eloquentSalesProformas->isEmpty()) {
            return [];
        }

        return $eloquentSalesProformas->map(fn($sale) => $this->mapToDomain($sale))->toArray();
    }

    public function findSaleWithUpdatedQuantities(int $referenceDocumentTypeId, string $referenceSerie, string $referenceCorrelative): ?array
    {
        $originalSale = EloquentSale::with([
            'saleArticles.article',
            'company',
            'branch',
            'documentType',
            'customer',
            'user',
            'userSale',
            'paymentType',
            'currencyType',
            'userAuthorized'
        ])
            ->where('document_type_id', $referenceDocumentTypeId)
            ->where('serie', $referenceSerie)
            ->where('document_number', $referenceCorrelative)
            ->first();

        if (!$originalSale) {
            return null;
        }

        // Obtener todas las notas de crédito relacionadas
        $creditNotes = EloquentSale::with(['saleArticles'])
            ->whereNotNull('reference_document_type_id')
            ->where('reference_document_type_id', $referenceDocumentTypeId)
            ->where('reference_serie', $referenceSerie)
            ->where('reference_correlative', $referenceCorrelative)
            ->where('status', 1)
            ->get();

        $hasCreditNotes = $creditNotes->isNotEmpty();

        // Calcular cantidades devueltas por artículo (sumando todas las notas de crédito)
        $returnedQuantities = [];
        if ($hasCreditNotes) {
            foreach ($creditNotes as $creditNote) {
                foreach ($creditNote->saleArticles as $article) {
                    $articleId = $article->article_id;
                    if (!isset($returnedQuantities[$articleId])) {
                        $returnedQuantities[$articleId] = 0;
                    }
                    // Sumar la cantidad devuelta de este artículo en esta nota de crédito
                    $returnedQuantities[$articleId] += $article->quantity;
                }
            }
        }

        // Calcular cantidades actualizadas para cada artículo
        $updatedArticles = [];

        foreach ($originalSale->saleArticles as $saleArticle) {
            $articleId = $saleArticle->article_id;
            $originalQuantity = $saleArticle->quantity;
            $returnedQuantity = $returnedQuantities[$articleId] ?? 0;
            $updatedQuantity = $originalQuantity - $returnedQuantity;

            // Omitir artículos completamente devueltos
            if ($returnedQuantity >= $originalQuantity) {
                continue;
            }

            // Si hay devolución, usar updated_quantity; si no, usar original_quantity
            $quantityForCalculation = $returnedQuantity > 0 ? $updatedQuantity : $originalQuantity;

            $articleSubtotal = $quantityForCalculation * $saleArticle->unit_price;

            $updatedArticles[] = [
                'sale_article_id' => $saleArticle->id,
                'article_id' => $articleId,
                'description' => $saleArticle->description,
                'measurement_unit' => $saleArticle->article->measurementUnit->name ?? null,
                'original_quantity' => $originalQuantity,
                'returned_quantity' => $returnedQuantity,
                'updated_quantity' => $updatedQuantity,
                'unit_price' => (float) $saleArticle->unit_price,
                'public_price' => (float) $saleArticle->public_price ?? null,
                'subtotal' => round($articleSubtotal, 2),
            ];
        }

        return [
            'sale' => $originalSale,
            'articles' => $updatedArticles,
            'has_credit_notes' => $hasCreditNotes
        ];

    }

    public function findAllCreditNotesByCustomerId(int $customerId): array
    {
        $creditNotes = EloquentSale::all()->where('customer_id', $customerId)->where('document_type_id', 7)->where('status', 1);

        if ($creditNotes->isEmpty()) {
            return [];
        }

        //return $creditNotes->map(fn($creditNote) => $this->mapToDomain($creditNote))->toArray();
        return $creditNotes->map(function ($creditNote) {
            return [
                'id' => $creditNote->id,
                'document_type_id' => $creditNote->document_type_id,
                'serie' => $creditNote->serie,
                'document_number' => $creditNote->document_number,
                'date' => $creditNote->date,
                'currency_id' => $creditNote->currencyType->id,
                'currency_name' => $creditNote->currencyType->name,
                'currency_symbol' => $creditNote->currencyType->commercial_symbol,
                'saldo' => $creditNote->saldo
            ];
        })->toArray();
    }

    public function findCreditNoteById(int $id): ?SaleCreditNote
    {
        $creditNote = EloquentSale::find($id);

        if (!$creditNote) {
            return null;
        }

        return $this->mapToDomainCreditNote($creditNote);
    }

    public function updateCreditNote(SaleCreditNote $saleCreditNote): ?SaleCreditNote
    {
        $creditNote = EloquentSale::find($saleCreditNote->getId());

        if (!$creditNote) {
            return null;
        }

        $creditNote->update($this->mapToArrayUpdateCreditNote($saleCreditNote));

        return $this->mapToDomainCreditNote($creditNote);
    }

    public function findAllSalesByCustomerId(int $customerId): ?array
    {
        $sales = EloquentSale::all()->where('customer_id', $customerId)->whereIn('document_type_id', [1, 3])->where('payment_status', 0)->sortByDesc('created_at');

        if ($sales->isEmpty()) {
            return null;
        }

        return $sales->map(fn($sale) => $this->mapToDomain($sale))->toArray();
    }

    private function mapToArray(Sale $sale): array
    {
        return [
            'company_id' => $sale->getCompany()->getId(),
            'branch_id' => $sale->getBranch()->getId(),
            'document_type_id' => $sale->getDocumentType()->getId(),
            'serie' => $sale->getSerie(),
            'document_number' => $sale->getDocumentNumber(),
            'parallel_rate' => $sale->getParallelRate(),
            'customer_id' => $sale->getCustomer()->getId(),
            'date' => $sale->getDate(),
            'due_date' => $sale->getDueDate(),
            'days' => $sale->getDays(),
            'user_id' => $sale->getUser()->getId(),
            'user_sale_id' => $sale->getUserSale()->getId(),
            'payment_type_id' => $sale->getPaymentType()->getId(),
            'observations' => $sale->getObservations(),
            'currency_type_id' => $sale->getCurrencyType()->getId(),
            'subtotal' => $sale->getSubtotal(),
            'igv' => $sale->getIgv(),
            'total' => $sale->getTotal(),
            'saldo' => $sale->getTotal(),
            'reference_document_type_id' => $sale->getIdProf(),
            'reference_serie' => $sale->getSerieProf(),
            'reference_correlative' => $sale->getCorrelativeProf(),
            'purchase_order' => $sale->getPurchaseOrder(),
            'user_authorized_id' => $sale->getUserAuthorized()?->getId()
        ];
    }

    private function mapToArrayUpdateCreditNote(SaleCreditNote $saleCreditNote): array
    {
        return [
            'company_id' => $saleCreditNote->getCompany()->getId(),
            'date' => $saleCreditNote->getDate(),
            'due_date' => $saleCreditNote->getDueDate(),
            'days' => $saleCreditNote->getDays(),
            'user_id' => $saleCreditNote->getUser()->getId(),
            'subtotal' => $saleCreditNote->getSubtotal(),
            'igv' => $saleCreditNote->getIgv(),
            'total' => $saleCreditNote->getTotal(),
            'saldo' => $saleCreditNote->getTotal(),
            'note_reason_id' => $saleCreditNote->getNoteReason()?->getId() ?? null
        ];
    }

    private function mapToArrayCreditNote(SaleCreditNote $saleCreditNote): array
    {
        return [
            'company_id' => $saleCreditNote->getCompany()->getId(),
            'branch_id' => $saleCreditNote->getBranch()->getId(),
            'document_type_id' => $saleCreditNote->getDocumentType()->getId(),
            'serie' => $saleCreditNote->getSerie(),
            'document_number' => $saleCreditNote->getDocumentNumber(),
            'parallel_rate' => $saleCreditNote->getParallelRate(),
            'customer_id' => $saleCreditNote->getCustomer()->getId(),
            'date' => $saleCreditNote->getDate(),
            'due_date' => $saleCreditNote->getDueDate(),
            'days' => $saleCreditNote->getDays(),
            'user_id' => $saleCreditNote->getUser()->getId(),
            'payment_type_id' => $saleCreditNote->getPaymentType()->getId(),
            'currency_type_id' => $saleCreditNote->getCurrencyType()->getId(),
            'subtotal' => $saleCreditNote->getSubtotal(),
            'igv' => $saleCreditNote->getIgv(),
            'total' => $saleCreditNote->getTotal(),
            'saldo' => $saleCreditNote->getTotal(),
            'reference_document_type_id' => $saleCreditNote->getReferenceDocumentTypeId(),
            'reference_serie' => $saleCreditNote->getReferenceSerie(),
            'reference_correlative' => $saleCreditNote->getReferenceCorrelative(),
            'note_reason_id' => $saleCreditNote->getNoteReason()?->getId() ?? null
        ];
    }

    private function mapToDomainCreditNote(EloquentSale $eloquentSale): SaleCreditNote
    {
        return new SaleCreditNote(
            id: $eloquentSale->id,
            company: $eloquentSale->company->toDomain($eloquentSale->company),
            branch: $eloquentSale->branch->toDomain($eloquentSale->branch),
            documentType: $eloquentSale->documentType->toDomain($eloquentSale->documentType),
            serie: $eloquentSale->serie,
            document_number: $eloquentSale->document_number,
            parallel_rate: $eloquentSale->parallel_rate,
            customer: $eloquentSale->customer->toDomain($eloquentSale->customer),
            date: $eloquentSale->date,
            due_date: $eloquentSale->due_date,
            days: $eloquentSale->days,
            user: $eloquentSale->user->toDomain($eloquentSale->user),
            paymentType: $eloquentSale->paymentType->toDomain($eloquentSale->paymentType),
            currencyType: $eloquentSale->currencyType->toDomain($eloquentSale->currencyType),
            subtotal: $eloquentSale->subtotal,
            igv: $eloquentSale->igv,
            total: $eloquentSale->total,
            saldo: $eloquentSale->saldo,
            amount_amortized: $eloquentSale->amount_amortized,
            status: $eloquentSale->status,
            payment_status: $eloquentSale->payment_status,
            is_locked: $eloquentSale->is_locked,
            reference_document_type_id: $eloquentSale->reference_document_type_id,
            reference_serie: $eloquentSale->reference_serie,
            reference_correlative: $eloquentSale->reference_correlative,
            note_reason: $eloquentSale->noteReason?->toDomain($eloquentSale->noteReason) ?? null
        );
    }

    private function mapToDomain(EloquentSale $eloquentSale): Sale
    {
        return new Sale(
            id: $eloquentSale->id,
            company: $eloquentSale->company->toDomain($eloquentSale->company),
            branch: $eloquentSale->branch->toDomain($eloquentSale->branch),
            documentType: $eloquentSale->documentType->toDomain($eloquentSale->documentType),
            serie: $eloquentSale->serie,
            document_number: $eloquentSale->document_number,
            parallel_rate: $eloquentSale->parallel_rate,
            customer: $eloquentSale->customer->toDomain($eloquentSale->customer),
            date: $eloquentSale->date,
            due_date: $eloquentSale->due_date,
            days: $eloquentSale->days,
            user: $eloquentSale->user->toDomain($eloquentSale->user),
            user_sale: $eloquentSale->userSale?->toDomain($eloquentSale->userSale),
            paymentType: $eloquentSale->paymentType->toDomain($eloquentSale->paymentType),
            observations: $eloquentSale->observations,
            currencyType: $eloquentSale->currencyType->toDomain($eloquentSale->currencyType),
            subtotal: $eloquentSale->subtotal,
            igv: $eloquentSale->igv,
            total: $eloquentSale->total,
            saldo: $eloquentSale->saldo,
            amount_amortized: $eloquentSale->amount_amortized,
            status: $eloquentSale->status,
            payment_status: $eloquentSale->payment_status,
            is_locked: $eloquentSale->is_locked,
            id_prof: $eloquentSale->reference_document_type_id,
            serie_prof: $eloquentSale->reference_serie,
            correlative_prof: $eloquentSale->reference_correlative,
            purchase_order: $eloquentSale->purchase_order,
            user_authorized: $eloquentSale->userAuthorized?->toDomain($eloquentSale->userAuthorized)
        );
    }

    private function buildDomainSale(EloquentSale $eloquentSale, Sale $domainSale): Sale
    {
        return new Sale(
            id: $eloquentSale->id,
            company: $domainSale->getCompany(),
            branch: $domainSale->getBranch(),
            documentType: $domainSale->getDocumentType(),
            serie: $eloquentSale->serie,
            document_number: $eloquentSale->document_number,
            parallel_rate: $eloquentSale->parallel_rate,
            customer: $domainSale->getCustomer(),
            date: $eloquentSale->date,
            due_date: $eloquentSale->due_date,
            days: $eloquentSale->days,
            user: $domainSale->getUser(),
            user_sale: $domainSale->getUserSale(),
            paymentType: $domainSale->getPaymentType(),
            observations: $eloquentSale->observations,
            currencyType: $domainSale->getCurrencyType(),
            subtotal: $eloquentSale->subtotal,
            igv: $eloquentSale->igv,
            total: $eloquentSale->total,
            saldo: $eloquentSale->saldo,
            amount_amortized: $eloquentSale->amount_amortized,
            status: $eloquentSale->status,
            payment_status: $eloquentSale->payment_status,
            is_locked: $eloquentSale->is_locked,
            id_prof: $eloquentSale->id_prof,
            serie_prof: $eloquentSale->series_prof,
            correlative_prof: $eloquentSale->correlative_prof,
            purchase_order: $eloquentSale->purchase_order,
            user_authorized: $eloquentSale->userAuthorized?->toDomain($eloquentSale->userAuthorized)
        );
    }

    private function buildDomainSaleCreditNote(EloquentSale $eloquentSale, SaleCreditNote $domainSale): SaleCreditNote
    {
        return new SaleCreditNote(
            id: $eloquentSale->id,
            company: $domainSale->getCompany(),
            branch: $domainSale->getBranch(),
            documentType: $domainSale->getDocumentType(),
            serie: $eloquentSale->serie,
            document_number: $eloquentSale->document_number,
            parallel_rate: $eloquentSale->parallel_rate,
            customer: $domainSale->getCustomer(),
            date: $eloquentSale->date,
            due_date: $eloquentSale->due_date,
            days: $eloquentSale->days,
            user: $domainSale->getUser(),
            paymentType: $domainSale->getPaymentType(),
            currencyType: $domainSale->getCurrencyType(),
            subtotal: $eloquentSale->subtotal,
            igv: $eloquentSale->igv,
            total: $eloquentSale->total,
            saldo: $eloquentSale->saldo,
            amount_amortized: $eloquentSale->amount_amortized,
            status: $eloquentSale->status,
            payment_status: $eloquentSale->payment_status,
            is_locked: $eloquentSale->is_locked,
            reference_document_type_id: $eloquentSale->reference_document_type_id,
            reference_serie: $eloquentSale->reference_serie,
            reference_correlative: $eloquentSale->reference_correlative,
            note_reason: $eloquentSale->noteReason?->toDomain($eloquentSale->noteReason)
        );
    }

    private function updateSaleBalance(EloquentSale $sale): void
    {
        $sale = $sale->fresh();
        Log::info('sale', $sale->toArray());
        DB::statement('CALL sp_actualiza_saldo_venta(?, ?, ?, ?)', [
            $sale->company_id,
            $sale->document_type_id,
            $sale->serie,
            $sale->document_number
        ]);

        $sale = $sale->fresh();
        Log::info('sale', $sale->toArray());
        $sale->payment_status = $sale->saldo == 0 ? 1 : 0;
        $sale->amount_amortized = $sale->total - $sale->saldo;
        $sale->save();
    }


}

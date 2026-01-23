<?php

namespace App\Modules\Sale\Infrastructure\Persistence;

use App\Modules\Advance\Infrastructure\Models\EloquentAdvance;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Entities\SaleCreditNote;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\SaleItemSerial\Application\UseCases\FindSerialBySaleAndArticleUseCase;
use App\Modules\SaleItemSerial\Infrastructure\Models\EloquentSaleItemSerial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentSaleRepository implements SaleRepositoryInterface
{
    public function findAll(int $companyId, ?string $start_date, ?string $end_date, ?string $description, ?int $status, ?int $payment_status, ?int $document_type_id)
    {
        $eloquentSale = EloquentSale::query()
            ->where('company_id', $companyId)
            ->where('document_type_id', '!=', 16)
            ->when($start_date && $end_date, fn($query) => $query->whereBetween('date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]))
            ->when($description, fn($query) => $query->where('serie', 'like', "%{$description}%")
                ->orWhere('document_number', 'like', "%{$description}%")
                ->orWhereHas('documentType', fn($query) => $query->where('description', 'like', "%{$description}%"))
                ->orWhereHas('paymentType', fn($query) => $query->where('name', 'like', "%{$description}%"))
                ->orWhereHas('customer', fn($query) => $query->where('name', 'like', "%{$description}%")
                    ->orWhere('lastname', 'like', "%{$description}%")
                    ->orWhere('company_name', 'like', "%{$description}%"))
                ->orWhereHas('user', fn($query) => $query->where('firstname', 'like', "%{$description}%")
                    ->orWhere('lastname', 'like', "%{$description}%")))
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->when($payment_status !== null, fn($query) => $query->where('payment_status', $payment_status))
            ->when($document_type_id !== null, fn($query) => $query->where('document_type_id', $document_type_id))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $eloquentSale->getCollection()->transform(fn($sale) => $this->mapToDomain($sale));

        return $eloquentSale;
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

        if (!is_null($eloquentSale->consignation_id)) {
            $dispatchNote = EloquentDispatchNote::where('id', $eloquentSale->consignation_id)->first();

            if ($dispatchNote) {
                $dispatchNote->stage = 1;
                $dispatchNote->save();
            }
        }

        if ($eloquentSale->reference_document_type_id == 9)
        {
            EloquentDispatchNote::where('serie', $eloquentSale->reference_serie)
                ->where('correlativo', $eloquentSale->reference_correlative)
                ->update([
                    'doc_referencia' => $eloquentSale->serie,
                    'num_referencia' => $eloquentSale->document_number,
                ]);
        }

        return $this->buildDomainSale($eloquentSale, $sale);
    }

    public function findByDocumentReference(int $document_type_id, string $serie, string $correlative): bool
    {
        $count = EloquentSale::where('reference_document_type_id', $document_type_id)
            ->where('reference_serie', $serie)
            ->where('reference_correlative', $correlative)
            ->where('document_type_id', 7)
            ->where('payment_status', 1)
            ->count();

        return $count > 0;
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
        $eloquentSale = EloquentSale::with('noteReason')->find($id);

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

    public function findAllProformas(?string $start_date, ?string $end_date, ?int $status, ?string $description)
    {
        $eloquentSalesProformas = EloquentSale::where('document_type_id', 16)
            ->orderBy('created_at', 'desc')
            ->when($start_date && $end_date, fn($query) => $query->whereBetween('date', [$start_date, $end_date]))
            ->when($description, fn($query) => $query->where('serie', 'like', "%{$description}%")
                ->orWhere('document_number', 'like', "%{$description}%")
                ->orWhereHas('documentType', fn($query) => $query->where('description', 'like', "%{$description}%"))
                ->orWhereHas('paymentType', fn($query) => $query->where('name', 'like', "%{$description}%"))
                ->orWhereHas('customer', fn($query) => $query->where('name', 'like', "%{$description}%")
                    ->orWhere('lastname', 'like', "%{$description}%")
                    ->orWhere('company_name', 'like', "%{$description}%"))
                ->orWhereHas('user', fn($query) => $query->where('firstname', 'like', "%{$description}%")
                    ->orWhere('lastname', 'like', "%{$description}%")))
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->paginate(10);

        $eloquentSalesProformas->getCollection()->transform(fn($sale) => $this->mapToDomain($sale));

        return $eloquentSalesProformas;
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
            
            $serials = EloquentSaleItemSerial::where('sale_id', $originalSale->id)
                ->where('article_id', $articleId)
                ->where('status', 1)
                ->pluck('serial')
                ->toArray();

            $updatedArticles[] = [
                'sale_article_id' => $saleArticle->id,
                'article_id' => $articleId,
                'cod_fab' => $saleArticle->article->cod_fab,
                'description' => $saleArticle->description,
                'measurement_unit' => $saleArticle->article->measurementUnit->name ?? null,
                'original_quantity' => $originalQuantity,
                'returned_quantity' => $returnedQuantity,
                'updated_quantity' => $updatedQuantity,
                'unit_price' => (float) $saleArticle->unit_price,
                'public_price' => (float) $saleArticle->public_price ?? null,
                'purchase_price' => (float) $saleArticle->purchase_price,
                'subtotal' => round($articleSubtotal, 2),
                'serie' => $serials
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

    public function findAllPendingSalesByCustomerId(int $customerId): ?array
    {
        $sales = EloquentSale::all()->where('customer_id', $customerId)->whereIn('document_type_id', [1, 3])->where('payment_status', 0)->sortByDesc('created_at');

        if ($sales->isEmpty()) {
            return null;
        }

        return $sales->map(fn($sale) => $this->mapToDomain($sale))->toArray();
    }

    /**
     * Este endpoint es para buscar todos los documentos de venta por cliente
     * @param int $customerId
     * @param mixed $payment_status
     * @param mixed $user_sale_id
     * @param mixed $start_date
     * @param mixed $end_date
     * @param mixed $document_type_id
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function findAllDocumentsByCustomerId(int $customerId, ?int $payment_status, ?int $user_sale_id, ?string $start_date, ?string $end_date, ?int $document_type_id)
    {
        // Query base para documentos paginados
        $query = EloquentSale::query()
            ->where('customer_id', $customerId)
            ->whereIn('document_type_id', [1, 3, 7, 8])
            ->when($payment_status !== null, fn($query) => $query->where('payment_status', $payment_status))
            ->when($user_sale_id !== null, fn($query) => $query->whereHas('userSale', fn($query) => $query->where('id', $user_sale_id)))
            ->when($start_date !== null, fn($query) => $query->where('date', '>=', $start_date))
            ->when($end_date !== null, fn($query) => $query->where('date', '<=', $end_date))
            ->when($document_type_id !== null, fn($query) => $query->where('document_type_id', $document_type_id))
            ->orderBy('created_at', 'desc');

        // Obtener documentos paginados
        $paginatedSales = $query->paginate(10);

        // Transformar la colección a entidades de dominio
        $paginatedSales->getCollection()->transform(function ($sale) {
            return $this->mapToDomain($sale);
        });

        // Calcular totales por moneda (sobre TODOS los documentos, no solo la página actual)
        $totalsQuery = EloquentSale::query()
            ->where('customer_id', $customerId)
            ->whereIn('document_type_id', [1, 3, 7, 8])
            ->when($payment_status !== null, fn($query) => $query->where('payment_status', $payment_status))
            ->when($user_sale_id !== null, fn($query) => $query->whereHas('userSale', fn($query) => $query->where('id', $user_sale_id)))
            ->when($document_type_id !== null, fn($query) => $query->where('document_type_id', $document_type_id))
            ->when($start_date !== null, fn($query) => $query->where('date', '>=', $start_date))
            ->when($end_date !== null, fn($query) => $query->where('date', '<=', $end_date))
            ->selectRaw('
                currency_type_id,
                SUM(total) as total_sales,
                SUM(amount_amortized) as total_paid,
                SUM(saldo) as total_balance
            ')
            ->groupBy('currency_type_id')
            ->get();

        // Inicializar totales
        $totals = [
            'soles' => [
                'total_sales' => 0.00,
                'total_paid' => 0.00,
                'total_balance' => 0.00
            ],
            'dolares' => [
                'total_sales' => 0.00,
                'total_paid' => 0.00,
                'total_balance' => 0.00
            ]
        ];

        // Asignar totales según el tipo de moneda
        foreach ($totalsQuery as $total) {
            if ($total->currency_type_id == 1) { // Soles
                $totals['soles']['total_sales'] = (float) $total->total_sales;
                $totals['soles']['total_paid'] = (float) $total->total_paid;
                $totals['soles']['total_balance'] = (float) $total->total_balance;
            } elseif ($total->currency_type_id == 2) { // Dólares
                $totals['dolares']['total_sales'] = (float) $total->total_sales;
                $totals['dolares']['total_paid'] = (float) $total->total_paid;
                $totals['dolares']['total_balance'] = (float) $total->total_balance;
            }
        }

        // Agregar totales al objeto paginado
        $paginatedSales->totals = $totals;

        return $paginatedSales;
    }

    public function updateStatus(int $id, int $status): void
    {
        EloquentSale::where('id', $id)->update(['status' => $status]);
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
            'reference_document_type_id' => $sale->getReferenceDocumentTypeId(),
            'reference_serie' => $sale->getReferenceSerie(),
            'reference_correlative' => $sale->getReferenceCorrelative(),
            'purchase_order' => $sale->getPurchaseOrder(),
            'user_authorized_id' => $sale->getUserAuthorized()?->getId(),
            'credit_amount' => $sale->getCreditAmount(),
            'coddetrac' => $sale->getCoddetrac(),
            'pordetrac' => $sale->getPordetrac(),
            'impdetracs' => $sale->getImpdetracs(),
            'impdetracd' => $sale->getImpdetracd(),
            'stretencion' => $sale->getStretencion(),
            'porretencion' => $sale->getPorretencion(),
            'impretens' => $sale->getImpretens(),
            'impretend' => $sale->getImpretend(),
            'consignation_id' => $sale->getConsignationId()
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
            'user_sale_id' => $saleCreditNote->getUserSale()->getId(),
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
            user_sale: $eloquentSale->userSale?->toDomain($eloquentSale->userSale),
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
            reference_document_type_id: $eloquentSale->reference_document_type_id,
            reference_serie: $eloquentSale->reference_serie,
            reference_correlative: $eloquentSale->reference_correlative,
            purchase_order: $eloquentSale->purchase_order,
            user_authorized: $eloquentSale->userAuthorized?->toDomain($eloquentSale->userAuthorized),
            credit_amount: $eloquentSale->credit_amount,
            coddetrac: $eloquentSale->coddetrac,
            pordetrac: $eloquentSale->pordetrac,
            impdetracs: $eloquentSale->impdetracs,
            impdetracd: $eloquentSale->impdetracd,
            stretencion: $eloquentSale->stretencion,
            porretencion: $eloquentSale->porretencion,
            impretens: $eloquentSale->impretens,
            impretend: $eloquentSale->impretend,
            total_costo_neto: $eloquentSale->total_costo_neto,
            consignation_id: $eloquentSale->consignation_id,
            note_reason: $eloquentSale->noteReason?->toDomain($eloquentSale->noteReason),
            sunat_status: $eloquentSale->estado_sunat,
            fecha_aceptacion: $eloquentSale->fecha_aceptacion,
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
            reference_document_type_id: $eloquentSale->reference_document_type_id,
            reference_serie: $eloquentSale->reference_serie,
            reference_correlative: $eloquentSale->reference_correlative,
            purchase_order: $eloquentSale->purchase_order,
            user_authorized: $eloquentSale->userAuthorized?->toDomain($eloquentSale->userAuthorized),
            credit_amount: $eloquentSale->credit_amount,
            coddetrac: $eloquentSale->coddetrac,
            pordetrac: $eloquentSale->pordetrac,
            impdetracs: $eloquentSale->impdetracs,
            impdetracd: $eloquentSale->impdetracd,
            stretencion: $eloquentSale->stretencion,
            porretencion: $eloquentSale->porretencion,
            impretens: $eloquentSale->impretens,
            impretend: $eloquentSale->impretend,
            consignation_id: $eloquentSale->consignation_id,
            note_reason: $eloquentSale->noteReason?->toDomain($eloquentSale->noteReason)
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
            user_sale: $domainSale->getUserSale(),
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
        DB::statement('CALL update_sale_balance(?, ?, ?, ?)', [
            $sale->company_id,
            $sale->document_type_id,
            $sale->serie,
            $sale->document_number
        ]);

        $sale = $sale->fresh();
        $sale->payment_status = $sale->saldo == 0 ? 1 : 0;
        $sale->amount_amortized = $sale->total - $sale->saldo;
        $sale->save();
    }

}

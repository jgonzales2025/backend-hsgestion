<?php

namespace App\Modules\Sale\Infrastructure\Persistence;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use Illuminate\Support\Facades\Log;

class EloquentSaleRepository implements SaleRepositoryInterface
{
    public function findAll(): array
    {
        $eloquentSale = EloquentSale::all()->sortByDesc('created_at');
        if ($eloquentSale->isEmpty()) {
            return [];
        }

        return $eloquentSale->map(fn($sale) => $this->mapToDomain($sale))->toArray();
    }

    public function save(Sale $sale): ?Sale
    {
        $eloquentSale = EloquentSale::create($this->mapToArray($sale));
        return $this->buildDomainSale($eloquentSale, $sale);
    }

    public function getLastDocumentNumber(): ?string
    {
        $sale = EloquentSale::all()
            ->sortByDesc('document_number')
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
            'inafecto' => $sale->getInafecto(),
            'igv' => $sale->getIgv(),
            'total' => $sale->getTotal(),
            'saldo' => $sale->getSaldo(),
            'status' => $sale->getStatus(),
            'amount_amortized' => $sale->getAmountAmortized(),
            'series_prof' => $sale->getSerieProf(),
            'correlative_prof' => $sale->getCorrelativeProf(),
            'purchase_order' => $sale->getPurchaseOrder()
        ];
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
            user_sale: $eloquentSale->userSale->toDomain($eloquentSale->userSale),
            paymentType: $eloquentSale->paymentType->toDomain($eloquentSale->paymentType),
            observations: $eloquentSale->observations,
            currencyType: $eloquentSale->currencyType->toDomain($eloquentSale->currencyType),
            subtotal: $eloquentSale->subtotal,
            inafecto: $eloquentSale->inafecto,
            igv: $eloquentSale->igv,
            total: $eloquentSale->total,
            saldo: $eloquentSale->saldo,
            amount_amortized: $eloquentSale->amount_amortized,
            status: $eloquentSale->status,
            payment_status: $eloquentSale->payment_status,
            is_locked: $eloquentSale->is_locked,
            serie_prof: $eloquentSale->series_prof,
            correlative_prof: $eloquentSale->correlative_prof,
            purchase_order: $eloquentSale->purchase_order
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
            inafecto: $eloquentSale->inafecto,
            igv: $eloquentSale->igv,
            total: $eloquentSale->total,
            saldo: $eloquentSale->saldo,
            amount_amortized: $eloquentSale->amount_amortized,
            status: $eloquentSale->status,
            payment_status: $eloquentSale->payment_status,
            is_locked: $eloquentSale->is_locked,
            serie_prof: $eloquentSale->series_prof,
            correlative_prof: $eloquentSale->correlative_prof,
            purchase_order: $eloquentSale->purchase_order
        );
    }


}

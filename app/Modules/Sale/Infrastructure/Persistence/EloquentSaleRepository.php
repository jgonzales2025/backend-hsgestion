<?php

namespace App\Modules\Sale\Infrastructure\Persistence;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;

class EloquentSaleRepository implements SaleRepositoryInterface
{
    public function findAll(): array
    {
        $eloquentSale = EloquentSale::all()->sortByDesc('created_at');
        if ($eloquentSale->isEmpty()) {
            return [];
        }

        return $eloquentSale->map(function ($sale) {
            return new Sale(
                id: $sale->id,
                company: $sale->company->toDomain($sale->company),
                branch: $sale->branch->toDomain($sale->branch),
                documentType: $sale->documentType->toDomain($sale->documentType),
                serie: $sale->serie,
                document_number: $sale->document_number,
                parallel_rate: $sale->parallel_rate,
                customer: $sale->customer->toDomain($sale->customer),
                date: $sale->date,
                due_date: $sale->due_date,
                days: $sale->days,
                user: $sale->user->toDomain($sale->user),
                user_sale: $sale->userSale->toDomain($sale->userSale),
                paymentType: $sale->paymentType->toDomain($sale->paymentType),
                observations: $sale->observations,
                currencyType: $sale->currencyType->toDomain($sale->currencyType),
                subtotal: $sale->subtotal,
                inafecto: $sale->inafecto,
                igv: $sale->igv,
                total: $sale->total,
                saldo: $sale->saldo,
                amount_amortized: $sale->amount_amortized,
                status: $sale->status,
                payment_status: $sale->payment_status,
                is_locked: $sale->is_locked,
                serie_prof: $sale->series_prof,
                correlative_prof: $sale->correlative_prof,
                purchase_order: $sale->purchase_order
            );
        })->toArray();
    }

    public function save(Sale $sale): ?Sale
    {
        $eloquentSale = EloquentSale::create([
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
            'amount_amortized' => $sale->getAmountAmortized(),
            'series_prof' => $sale->getSerieProf(),
            'correlative_prof' => $sale->getCorrelativeProf(),
            'purchase_order' => $sale->getPurchaseOrder()
        ]);

        return new Sale(
            id: $eloquentSale->id,
            company: $sale->getCompany(),
            branch: $sale->getBranch(),
            documentType: $sale->getDocumentType(),
            serie: $eloquentSale->serie,
            document_number: $eloquentSale->document_number,
            parallel_rate: $eloquentSale->parallel_rate,
            customer: $sale->getCustomer(),
            date: $eloquentSale->date,
            due_date: $eloquentSale->due_date,
            days: $eloquentSale->days,
            user: $sale->getUser(),
            user_sale: $sale->getUserSale(),
            paymentType: $sale->getPaymentType(),
            observations: $eloquentSale->observations,
            currencyType: $sale->getCurrencyType(),
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
}

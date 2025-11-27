<?php

namespace App\Modules\DocumentEntryGuide\Domain\Entities;

class DocumentEntryGuide
{
    private int $id;
    private int $entry_guide_id;
    private string $guide_serie_supplier;
    private string $guide_correlative_supplier;
    private string $invoice_serie_supplier;
    private string $invoice_correlative_supplier;

    public function __construct(
         int $id,
         int $entry_guide_id,
         string $guide_serie_supplier,
         string $guide_correlative_supplier,
         string $invoice_serie_supplier,
         string $invoice_correlative_supplier,
    ) {
        $this->id = $id;
        $this->entry_guide_id = $entry_guide_id;
        $this->guide_serie_supplier = $guide_serie_supplier;
        $this->guide_correlative_supplier = $guide_correlative_supplier;
        $this->invoice_serie_supplier = $invoice_serie_supplier;
        $this->invoice_correlative_supplier = $invoice_correlative_supplier;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getEntryGuideId(): int
    {
        return $this->entry_guide_id;
    }
    public function getGuideSerieSupplier(): string
    {
        return $this->guide_serie_supplier;
    }
    public function getGuideCorrelativeSupplier(): string
    {
        return $this->guide_correlative_supplier;
    }
    public function getInvoiceSerieSupplier(): string
    {
        return $this->invoice_serie_supplier;
    }
    public function getInvoiceCorrelativeSupplier(): string
    {
        return $this->invoice_correlative_supplier;
    }
}

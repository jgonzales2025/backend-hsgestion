<?php
namespace App\Modules\DocumentEntryGuide\application\DTOS;

class DocumentEntryGuideDTO
{
        public int $entry_guide_id;
        public string $guide_serie_supplier;
        public string $guide_correlative_supplier;
        public string $invoice_serie_supplier;
        public string $invoice_correlative_supplier;

    public function __construct(array $data) {

        $this->entry_guide_id = $data['entry_guide_id'];
        $this->guide_serie_supplier = $data['guide_serie_supplier'];
        $this->guide_correlative_supplier = $data['guide_correlative_supplier'];
        $this->invoice_serie_supplier = $data['invoice_serie_supplier'] ?? '';
        $this->invoice_correlative_supplier = $data['invoice_correlative_supplier'];
    }
}
<?php
namespace App\Modules\DocumentEntryGuide\application\DTOS;

class DocumentEntryGuideDTO
{
        public int $entry_guide_id;
        public int $reference_document_id;
        public string $reference_serie;
        public string $reference_correlative;

    public function __construct(array $data) {

        $this->entry_guide_id = $data['entry_guide_id'];
        $this->reference_document_id = $data['reference_document_id'];
        $this->reference_serie = $data['reference_serie'] ?? '';
        $this->reference_correlative = $data['reference_correlative'];
    }
}
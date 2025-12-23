<?php

namespace App\Modules\DocumentEntryGuide\Domain\Entities;

class DocumentEntryGuide
{
    private int $id;
    private int $entry_guide_id;
    private int $reference_document_id;
    private string $reference_serie;
    private string $reference_correlative;

    public function __construct(
         int $id,
         int $entry_guide_id,
         int $reference_document_id,
         string $reference_serie,
         string $reference_correlative,
    ) {
        $this->id = $id;
        $this->entry_guide_id = $entry_guide_id;
        $this->reference_document_id = $reference_document_id;
        $this->reference_serie = $reference_serie;
        $this->reference_correlative = $reference_correlative;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getEntryGuideId(): int
    {
        return $this->entry_guide_id;
    }
    public function getReferenceDocumentId(): int
    {
        return $this->reference_document_id;
    }
    public function getReferenceSerie(): string
    {
        return $this->reference_serie;
    }
    public function getReferenceCorrelative(): string
    {
        return $this->reference_correlative;
    }
}

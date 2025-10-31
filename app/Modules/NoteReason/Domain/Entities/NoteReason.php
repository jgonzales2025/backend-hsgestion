<?php

namespace App\Modules\NoteReason\Domain\Entities;

use App\Modules\DocumentType\Domain\Entities\DocumentType;

class NoteReason
{
    private int $id;
    private string $cod_sunat;
    private string $description;
    private int $document_type_id;
    private int $stock;
    private int $status;

    public function __construct(int $id, string $cod_sunat, string $description, int $document_type_id, int $stock, int $status)
    {
        $this->id = $id;
        $this->cod_sunat = $cod_sunat;
        $this->description = $description;
        $this->document_type_id = $document_type_id;
        $this->stock = $stock;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getCodSunat(): string { return $this->cod_sunat; }
    public function getDescription(): string { return $this->description; }
    public function getDocumentTypeId(): int { return $this->document_type_id; }
    public function getStock(): int { return $this->stock; }
    public function getStatus(): int { return $this->status; }
}

<?php

namespace App\Modules\Serie\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\DocumentType\Domain\Entities\DocumentType;

class Serie
{
    private int $id;
    private Company $company;
    private string $serie_number;
    private Branch $branch;
    private DocumentType $elec_document_type;
    private DocumentType $dir_document_type;
    private int $status;

    public function __construct(int $id, Company $company, string $serie_number, Branch $branch, DocumentType $elec_document_type, DocumentType $dir_document_type, $status)
    {
        $this->id = $id;
        $this->company = $company;
        $this->serie_number = $serie_number;
        $this->branch = $branch;
        $this->elec_document_type = $elec_document_type;
        $this->dir_document_type = $dir_document_type;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getCompany(): Company { return $this->company; }
    public function getSerieNumber(): string { return $this->serie_number; }
    public function getBranch(): Branch { return $this->branch; }
    public function getElecDocumentType(): DocumentType { return $this->elec_document_type; }
    public function getDirDocumentType(): DocumentType { return $this->dir_document_type; }
    public function getStatus(): int { return $this->status;}
}

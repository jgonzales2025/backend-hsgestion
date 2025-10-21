<?php

namespace App\Modules\Serie\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\DocumentType\Domain\Entities\DocumentType;

class Serie
{
    private int $id;
    private Company $company;
    private $serie_number;
    private Branch $branch;
    private DocumentType $document_type;
    private int $status;

    public function __construct(int $id, Company $company, $serie_number, Branch $branch, DocumentType $document_type, $status)
    {
        $this->id = $id;
        $this->company = $company;
        $this->serie_number = $serie_number;
        $this->branch = $branch;
        $this->document_type = $document_type;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getCompany(): Company { return $this->company; }
    public function getSerieNumber(): string { return $this->serie_number; }
    public function getBranch(): Branch { return $this->branch; }
    public function getDocumentType(): DocumentType { return $this->document_type; }
    public function getStatus(): int { return $this->status;}
}

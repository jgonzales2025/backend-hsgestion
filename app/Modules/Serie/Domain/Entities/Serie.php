<?php

namespace App\Modules\Serie\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\DocumentType\Domain\Entities\DocumentType;

class Serie
{
    private int $id;
    private int $company_id;
    private string $serie_number;
    private int $branch_id;
    private int $elec_document_type_id;
    private int $dir_document_type_id;
    private int $status;

    public function __construct(int $id, int $company_id, string $serie_number, int $branch_id, int $elec_document_type_id, int $dir_document_type_id, int $status)
    {
        $this->id = $id;
        $this->company_id = $company_id;
        $this->serie_number = $serie_number;
        $this->branch_id = $branch_id;
        $this->elec_document_type_id = $elec_document_type_id;
        $this->dir_document_type_id = $dir_document_type_id;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getCompanyId(): int { return $this->company_id; }
    public function getSerieNumber(): string { return $this->serie_number; }
    public function getBranchId(): int { return $this->branch_id; }
    public function getElecDocumentTypeId(): int { return $this->elec_document_type_id; }
    public function getDirDocumentTypeId(): int { return $this->dir_document_type_id; }
    public function getStatus(): int { return $this->status;}
}

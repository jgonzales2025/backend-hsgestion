<?php

namespace App\Modules\Driver\Domain\Entities;

class Driver
{
    private ?int $id;
    private int $customer_document_type_id;
    private string $doc_number;
    private string $name;
    private string $pat_surname;
    private string $mat_surname;
    private ?int $status;
    private ?string $license;
    private ?string $document_type_name;

    public function __construct(?int $id, int $customer_document_type_id, string $doc_number, string $name, string $pat_surname, string $mat_surname, ?int $status = 1, ?string $license, ?string $document_type_name)
    {
        $this->id = $id;
        $this->customer_document_type_id = $customer_document_type_id;
        $this->doc_number = $doc_number;
        $this->name = $name;
        $this->pat_surname = $pat_surname;
        $this->mat_surname = $mat_surname;
        $this->status = $status;
        $this->license = $license;
        $this->document_type_name = $document_type_name;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getCustomerDocumentTypeId(): int
    {
        return $this->customer_document_type_id;
    }

    public function getDocNumber(): string
    {
        return $this->doc_number;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPatSurname(): string
    {
        return $this->pat_surname;
    }

    public function getMatSurname(): string
    {
        return $this->mat_surname;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getLicense(): string|null
    {
        return $this->license;
    }

    public function getDocumentTypeName(): ?string
    {
        return $this->document_type_name;
    }

}

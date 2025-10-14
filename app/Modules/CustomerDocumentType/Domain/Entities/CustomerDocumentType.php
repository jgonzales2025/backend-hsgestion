<?php

namespace App\Modules\CustomerDocumentType\Domain\Entities;

class CustomerDocumentType
{
    private int $id;
    private int $cod_sunat;
    private string $description;
    private string $abbreviation;
    private int $st_driver;
    private int $status;

    public function __construct(int $id, int $cod_sunat, string $description, string $abbreviation, int $st_driver, int $status)
    {
        $this->id = $id;
        $this->cod_sunat = $cod_sunat;
        $this->description = $description;
        $this->abbreviation = $abbreviation;
        $this->st_driver = $st_driver;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getCodSunat(): int { return $this->cod_sunat; }
    public function getDescription(): string { return $this->description; }
    public function getAbbreviation(): string { return $this->abbreviation; }
    public function getStDriver(): int { return $this->st_driver; }
    public function getStatus(): int { return $this->status;}
}

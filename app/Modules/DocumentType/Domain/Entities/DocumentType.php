<?php

namespace App\Modules\DocumentType\Domain\Entities;

class DocumentType
{
    private int $id;
    private string $cod_sunat;
    private string $description;
    private string $abbreviation;
    private int $st_sales;
    private int $st_purchases;
    private int $st_collections;
    private int $status;

    public function __construct(int $id, string $cod_sunat, string $description, string $abbreviation, int $st_sales, int $st_purchases, int $st_collections, int $status)
    {
        $this->id = $id;
        $this->cod_sunat = $cod_sunat;
        $this->description = $description;
        $this->abbreviation = $abbreviation;
        $this->st_sales = $st_sales;
        $this->st_purchases = $st_purchases;
        $this->st_collections = $st_collections;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getCodSunat(): string { return $this->cod_sunat; }
    public function getDescription(): string { return $this->description; }
    public function getAbbreviation(): string { return $this->abbreviation; }
    public function getStSales(): int { return $this->st_sales; }
    public function getStPurchases(): int { return $this->st_purchases; }
    public function getStCollections(): int { return $this->st_collections; }
    public function getStatus(): int { return $this->status; }
}

<?php

namespace App\Modules\DetailPurchaseGuides\Domain\Entities;

class DetailPurchaseGuide
{
    private ?int $id;
    private int $article_id;
    private int $purchase_id;
    private string $description;
    private int $cantidad;
    private float $precio_costo;
    private float $descuento;
    private float $sub_total;

    public function __construct(
        ?int $id,
        int $article_id,
        int $purchase_id,
        string $description,
        int $cantidad,
        float $precio_costo,
        float $descuento,
        float $sub_total,
    ){
         
        $this->id = $id;
        $this->article_id = $article_id;
        $this->purchase_id = $purchase_id;
        $this->description = $description;
        $this->cantidad = $cantidad;
        $this->precio_costo = $precio_costo;
        $this->descuento = $descuento;
        $this->sub_total = $sub_total;
    
    }
    public function getId(): int|null
    {
        return $this->id;
    }

    public function getArticleId(): int
    {
        return $this->article_id;
    }
    public function getPurchaseId(): int
    {
        return $this->purchase_id;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getCantidad(): int
    {
        return $this->cantidad;
    }
    public function getPrecioCosto(): float
    {
        return $this->precio_costo;
    }
    public function getDescuento(): float
    {
        return $this->descuento;
    }
    public function getSubTotal(): float
    {
        return $this->sub_total;
    }



}
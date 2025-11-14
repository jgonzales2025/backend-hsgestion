<?php

namespace App\Modules\DetailPurchaseGuides\Application\DTOS;

class DetailPurchaseGuideDTO
{
    public int $article_id;
    public int $purchase_id;
    public string $description;
    public int $cantidad;
    public float $precio_costo;
    public float $descuento;
    public float $sub_total;

    public function __construct(array $array){
      $this->article_id = $array['article_id'];
      $this->purchase_id = $array['purchase_id'];
      $this->description = $array['description'];
      $this->cantidad = $array['cantidad'];
      $this->precio_costo = $array['precio_costo'];
      $this->descuento = $array['descuento'];
      $this->sub_total = $array['sub_total'];

    }
}
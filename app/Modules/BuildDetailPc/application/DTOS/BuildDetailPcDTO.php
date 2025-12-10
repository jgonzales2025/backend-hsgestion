<?php

namespace App\Modules\BuildDetailPc\application\DTOS;

class BuildDetailPcdto
{
    public int $build_pc_id;
    public int $article_id;
    public int $quantity;
    public float $price;
    public float $subtotal;
    public function __construct(array $data) {
        $this->build_pc_id = $data['build_pc_id'];
        $this->article_id = $data['article_id'];
        $this->quantity = $data['quantity'];
    }
}
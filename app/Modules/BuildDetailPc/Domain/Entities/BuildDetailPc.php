<?php
namespace App\Modules\BuildDetailPc\Domain\Entities;

class BuildDetailPc
{
    private ?int $id;
    private int $build_pc_id;
    private int $article_id;
    private int $quantity;
    private float $price;
    private float $subtotal;

    public function __construct(?int $id, int $build_pc_id, int $article_id, int $quantity, float $price, float $subtotal)
    {
        $this->id = $id;
        $this->build_pc_id = $build_pc_id;
        $this->article_id = $article_id;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->subtotal = $subtotal;
    }
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getBuildPcId(): int
    {
        return $this->build_pc_id;
    }
    public function getArticleId(): int
    {
        return $this->article_id;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

}
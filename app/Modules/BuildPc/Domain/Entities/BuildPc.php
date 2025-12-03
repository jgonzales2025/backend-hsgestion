<?php

namespace App\Modules\BuildPc\Domain\Entities;

class BuildPc
{
    private ?int $id;
    private string $name;
    private string $description;
    private float $total_price;
    private int $user_id;
    private bool $status;
    private ?int $quantity;
    private ?int $article_ensamb_id;



    public function __construct(
        ?int $id,
        string $name,
        string $description,
        float $total_price,
        int $user_id,
        bool $status,
        ?int $quantity,
        ?int $article_ensamb_id,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->total_price = $total_price;
        $this->user_id = $user_id;
        $this->status = $status;
        $this->quantity = $quantity;
        $this->article_ensamb_id = $article_ensamb_id;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTotalPrice(): float
    {
        return $this->total_price;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getQuantity(): int|null
    {
        return $this->quantity;
    }
    public function getArticleEnsambId(): int|null
    {
        return $this->article_ensamb_id;
    }
}

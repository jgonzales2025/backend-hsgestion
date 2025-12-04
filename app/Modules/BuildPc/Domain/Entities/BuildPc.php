<?php

namespace App\Modules\BuildPc\Domain\Entities;

class BuildPc
{
    private ?int $id;
    private int $company_id;
    private string $name;
    private string $description;
    private float $total_price;
    private int $user_id;
    private bool $status;


    public function __construct(
        ?int $id,
        int $company_id,
        string $name,
        string $description,
        float $total_price,
        int $user_id,
        bool $status,

    ) {
        $this->id = $id;
        $this->company_id = $company_id;
        $this->name = $name;
        $this->description = $description;
        $this->total_price = $total_price;
        $this->user_id = $user_id;
        $this->status = $status;
    }

    public function getId(): int|null
    {
        return $this->id;
    }
    public function getCompanyId(): int
    {
        return $this->company_id;
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
}

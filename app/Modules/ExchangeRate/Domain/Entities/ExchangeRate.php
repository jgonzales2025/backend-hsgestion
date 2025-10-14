<?php

namespace App\Modules\ExchangeRate\Domain\Entities;

class ExchangeRate
{
    private ?int $id;
    private ?string $date;
    private ?float $purchase_rate;
    private ?float $sale_rate;
    private float $parallel_rate;

    public function __construct(?int $id, ?string $date, ?float $purchase_rate, ?float $sale_rate, float $parallel_rate)
    {
        $this->id = $id;
        $this->date = $date;
        $this->purchase_rate = $purchase_rate;
        $this->sale_rate = $sale_rate;
        $this->parallel_rate = $parallel_rate;
    }

    public function getId(): int|null { return $this->id; }
    public function getDate(): string|null { return $this->date; }
    public function getPurchaseRate(): float|null { return $this->purchase_rate; }
    public function getSaleRate(): float|null { return $this->sale_rate; }
    public function getParallelRate(): float { return $this->parallel_rate; }
}

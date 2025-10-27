<?php

namespace App\Modules\MonthlyClosure\Domain\Entities;

class MonthlyClosure
{
    private int $id;
    private int $year;
    private int $month;
    private ?int $st_purchases;
    private ?int $st_sales;
    private ?int $st_cash;

    public function __construct(int $id, int $year, int $month, ?int $st_purchases, ?int $st_sales, ?int $st_cash)
    {
        $this->id = $id;
        $this->year = $year;
        $this->month = $month;
        $this->st_purchases = $st_purchases;
        $this->st_sales = $st_sales;
        $this->st_cash = $st_cash;
    }

    public function getId(): int { return $this->id; }
    public function getYear(): int { return $this->year; }
    public function getMonth(): int { return $this->month; }
    public function getStPurchases(): ?int { return $this->st_purchases; }
    public function getStSales(): ?int { return $this->st_sales; }
    public function getStCash(): ?int { return $this->st_cash; }
}

<?php

namespace App\Modules\Company\Domain\Entities;

class UpdateCompany{
    private int $default_currency_type_id;
    private float $min_profit;
    private float $max_profit;

    public function __construct(int $default_currency_type_id, float $min_profit, float $max_profit){
        $this->default_currency_type_id = $default_currency_type_id;
        $this->min_profit = $min_profit;
        $this->max_profit = $max_profit;
    }

    public function getDefaultCurrencyTypeId(): int
    {
        return $this->default_currency_type_id;
    }
    public function getMinProfit(): float
    {
        return $this->min_profit;
    }
    public function getMaxProfit(): float
    {
        return $this->max_profit;
    }
}
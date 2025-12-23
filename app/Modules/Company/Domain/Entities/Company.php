<?php

namespace App\Modules\Company\Domain\Entities;

use App\Modules\CurrencyType\Domain\Entities\CurrencyType;

class Company
{
    private int $id;
    private string $ruc;
    private string $company_name;
    private string $address;
    private string $start_date;
    private string $ubigeo;
    private int $status;
    private CurrencyType $default_currency_type;
    private float $min_profit;
    private float $max_profit;
    private string $detrac_cta_banco;

    public function __construct(
        int $id,
        string $ruc,
        string $company_name,
        string $address,
        string $start_date,
        string $ubigeo,
        int $status,
        CurrencyType $default_currency_type,
        float $min_profit,
        float $max_profit,
        string $detrac_cta_banco
    ) {
        $this->id = $id;
        $this->ruc = $ruc;
        $this->company_name = $company_name;
        $this->address = $address;
        $this->start_date = $start_date;
        $this->ubigeo = $ubigeo;
        $this->status = $status;
        $this->default_currency_type = $default_currency_type;
        $this->min_profit = $min_profit;
        $this->max_profit = $max_profit;
        $this->detrac_cta_banco = $detrac_cta_banco;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getRuc(): string
    {
        return $this->ruc;
    }

    public function getCompanyName(): string
    {
        return $this->company_name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getStartDate(): string
    {
        return $this->start_date;
    }
    public function getUbigeo(): string
    {
        return $this->ubigeo;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    public function getDefaultCurrencyType(): CurrencyType
    {
        return $this->default_currency_type;
    }
    public function getMinProfit(): float
    {
        return $this->min_profit;
    }
    public function getMaxProfit(): float
    {
        return $this->max_profit;
    }

    public function getDetracCtaBanco(): string
    {
        return $this->detrac_cta_banco;
    }

}

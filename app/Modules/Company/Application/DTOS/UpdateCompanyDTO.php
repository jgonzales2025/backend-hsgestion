<?php

namespace App\Modules\Company\Application\DTOS;

class UpdateCompanyDTO
{
    public int $default_currency_type_id;
    public float $min_profit;
    public float $max_profit;
    public string $detrac_cta_banco;
    
    public function __construct(array $data)
    {
        $this->default_currency_type_id = $data['default_currency_type_id'];
        $this->min_profit = $data['min_profit'];
        $this->max_profit = $data['max_profit'];
        $this->detrac_cta_banco = $data['detrac_cta_banco'];
    }
}

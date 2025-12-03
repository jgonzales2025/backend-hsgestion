<?php

namespace App\Modules\PaymentMethodsSunat\Domain\Entities;

class PaymentMethodSunat
{
    private int $cod;
    private string $des;

    public function __construct(
        int $cod,
        string $des
    ) {
        $this->cod = $cod;
        $this->des = $des;
    }

    public function getCod(): int
    {
        return $this->cod;
    }

    public function getDes(): string
    {
        return $this->des;
    }
}
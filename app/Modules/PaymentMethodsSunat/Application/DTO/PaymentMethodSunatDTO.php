<?php

namespace App\Modules\PaymentMethodsSunat\Application\DTO;

class PaymentMethodSunatDTO
{
    public function __construct(
        public int $cod,
        public string $des
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            cod: $data['cod'],
            des: $data['des']
        );
    }
}

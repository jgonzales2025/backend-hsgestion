<?php

namespace App\Modules\Detraction\Domain\Entities;

class Detraction
{
    public int $id;
    public int $cod_sunat;
    public string $description;
    public float $percentage;

    public function __construct(
        int $id,
        int $cod_sunat,
        string $description,
        float $percentage,
    ) {
        $this->id = $id;
        $this->cod_sunat = $cod_sunat;
        $this->description = $description;
        $this->percentage = $percentage;
    }

    public function getId(): int { return $this->id; }
    public function getCodSunat(): int { return $this->cod_sunat; }
    public function getDescription(): string { return $this->description; }
    public function getPercentage(): float { return $this->percentage; }
}
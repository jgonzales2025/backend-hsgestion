<?php

namespace App\Modules\Ubigeo\Provinces\Domain\Entities;

class Province
{
    private int $coddep;
    private int $codpro;
    private string $nompro;

    public function __construct(int $coddep, int $codpro, string $nompro)
    {
        $this->coddep = $coddep;
        $this->codpro = $codpro;
        $this->nompro = $nompro;
    }

    public function getCoddep(): int { return $this->coddep; }
    public function getCodpro(): int { return $this->codpro; }
    public function getNompro(): string { return $this->nompro; }
}

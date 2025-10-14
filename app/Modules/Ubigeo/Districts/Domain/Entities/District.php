<?php

namespace App\Modules\Ubigeo\Districts\Domain\Entities;

class District
{
    private int $coddep;
    private int $codpro;
    private int $coddis;
    private string $nomdis;

    public function __construct(int $coddep, int $codpro, int $coddis, string $nomdis)
    {
        $this->coddep = $coddep;
        $this->codpro = $codpro;
        $this->coddis = $coddis;
        $this->nomdis = $nomdis;
    }

    public function getCoddep(): int { return $this->coddep; }
    public function getCodpro(): int { return $this->codpro; }
    public function getCoddis(): int { return $this->coddis; }
    public function getNomdis(): string { return $this->nomdis; }
}

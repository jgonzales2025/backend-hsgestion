<?php

namespace App\Modules\Departments\Domain\Entities;

class Department
{
    private int $coddep;
    private string $nomdep;

    public function __construct(int $coddep, string $nomdep)
    {
        $this->coddep = $coddep;
        $this->nomdep = $nomdep;
    }

    public function getCoddep(): int { return $this->coddep; }
    public function getNomdep(): string { return $this->nomdep; }
}

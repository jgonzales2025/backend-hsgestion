<?php

namespace Modules\Kardex\Domain\Entites;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;

class Kardex
{
    private int $id;
    private ?Company $company;
    private ?Branch $branch;
    private string $codigo;
    private bool $is_today;
    private string $description;
    private string $before_fech;
    private string $after_fech;
    private bool $status;
   

    public function __construct(
        int $id,
        ?Company $company,
        ?Branch $branch,
        string $codigo,
        bool $is_today,
        string $description,
        string $before_fech,
        string $after_fech,
        bool $status,
    ) {
        $this->id = $id;
        $this->company = $company;
        $this->branch = $branch;
        $this->codigo = $codigo;
        $this->is_today = $is_today;
        $this->description = $description;
        $this->before_fech = $before_fech;
        $this->after_fech = $after_fech;
        $this->status = $status;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getCompany(): ?Company
    {
        return $this->company;
    }
    public function getBranch(): ?Branch
    {
        return $this->branch;
    }
    public function getCodigo(): string
    {
        return $this->codigo;
    }
    public function getIsToday(): bool
    {
        return $this->is_today;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getBeforeFech(): string
    {
        return $this->before_fech;
    }
    public function getAfterFech(): string
    {
        return $this->after_fech;
    }
    public function getStatus(): bool
    {
        return $this->status;
    }
}

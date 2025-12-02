<?php

namespace App\Modules\Withholding\Domain\Interface;

use App\Modules\Withholding\Domain\Entities\Withholding;

interface WithholdingRepositoryInterface
{
    public function findByDate(string $date): ?Withholding;
}
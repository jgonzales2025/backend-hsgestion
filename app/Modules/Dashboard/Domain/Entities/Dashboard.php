<?php

namespace App\Modules\Dashboard\Domain\Entities;

use App\Modules\Sale\Domain\Entities\Sale;

class Dashboard
{
    private Sale $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function getSale(): Sale
    {
        return $this->sale;
    }
}
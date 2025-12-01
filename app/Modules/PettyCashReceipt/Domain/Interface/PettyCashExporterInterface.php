<?php

namespace App\Modules\PettyCashReceipt\Domain\Interface;

use Illuminate\Support\Collection;

interface PettyCashExporterInterface
{
    public function export(array $data): string;
}

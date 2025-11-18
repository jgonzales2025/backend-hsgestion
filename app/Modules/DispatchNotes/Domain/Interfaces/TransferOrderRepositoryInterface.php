<?php

namespace App\Modules\DispatchNotes\Domain\Interfaces;

use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;

interface TransferOrderRepositoryInterface
{
    public function save(TransferOrder $transferOrder): TransferOrder;
    public function getLastDocumentNumber(string $serie): ?string;
}
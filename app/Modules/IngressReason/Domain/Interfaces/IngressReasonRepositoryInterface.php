<?php

namespace App\Modules\IngressReason\Domain\Interfaces;

use App\Modules\IngressReason\Domain\Entities\IngressReason;

interface IngressReasonRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?IngressReason;
}

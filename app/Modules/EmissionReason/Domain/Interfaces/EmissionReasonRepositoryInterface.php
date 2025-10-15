<?php

namespace App\Modules\EmissionReason\Domain\Interfaces;

interface EmissionReasonRepositoryInterface
{
    public function findAll(): array;
}

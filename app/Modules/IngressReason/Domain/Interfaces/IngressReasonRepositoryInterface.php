<?php

namespace App\Modules\IngressReason\Domain\Interfaces;

interface IngressReasonRepositoryInterface
{
    public function findAll(): array;
}

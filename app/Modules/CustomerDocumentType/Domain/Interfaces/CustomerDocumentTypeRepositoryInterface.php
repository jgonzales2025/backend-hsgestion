<?php

namespace App\Modules\CustomerDocumentType\Domain\Interfaces;

interface CustomerDocumentTypeRepositoryInterface
{
    public function findAllForDrivers(): array;
    public function findAllDrivers(): array;
}

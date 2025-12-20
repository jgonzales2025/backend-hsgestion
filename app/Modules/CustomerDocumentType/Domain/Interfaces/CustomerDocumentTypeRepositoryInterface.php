<?php

namespace App\Modules\CustomerDocumentType\Domain\Interfaces;

use App\Modules\CustomerDocumentType\Domain\Entities\CustomerDocumentType;

interface CustomerDocumentTypeRepositoryInterface
{
    public function findAllForDrivers(): array;
    public function findAllDrivers(): array;
    public function findById(int $id): CustomerDocumentType;
}

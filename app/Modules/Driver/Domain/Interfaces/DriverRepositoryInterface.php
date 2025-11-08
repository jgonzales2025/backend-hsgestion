<?php

namespace App\Modules\Driver\Domain\Interfaces;

use App\Modules\Driver\Domain\Entities\Driver;

interface DriverRepositoryInterface
{
    public function findAllDrivers(?string $description): array;
    public function save(Driver $driver): ?Driver;
    public function findById(int $id): ?Driver;
    public function update(Driver $driver): void;
    public function findDriverByDocumentNumber(string $documentNumber): ?Driver;
}

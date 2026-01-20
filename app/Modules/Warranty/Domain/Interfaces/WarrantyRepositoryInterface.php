<?php

namespace App\Modules\Warranty\Domain\Interfaces;

use App\Modules\Warranty\Domain\Entities\TechnicalSupport;
use App\Modules\Warranty\Domain\Entities\UpdateWarranty;
use App\Modules\Warranty\Domain\Entities\Warranty;

interface WarrantyRepositoryInterface
{
    public function findAll(?string $description, ?string $startDate, ?string $endDate, ?int $warrantyStatusId);
    public function save(Warranty $warranty): int;
    public function findById(int $id);
    public function getLastDocumentNumber(string $serie): ?string;
    public function saveTechnicalSupport(TechnicalSupport $technicalSupport): int;
    public function updateWarranty(UpdateWarranty $updateWarranty, int $id): ?int;
}
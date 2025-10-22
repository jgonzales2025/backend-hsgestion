<?php

namespace App\Modules\Serie\Domain\Interfaces;

use App\Modules\Serie\Domain\Entities\Serie;

interface SerieRepositoryInterface
{
    public function findByDocumentType(int $documentType, int $branch_id): ?Serie;
}

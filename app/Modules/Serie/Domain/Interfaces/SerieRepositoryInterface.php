<?php

namespace App\Modules\Serie\Domain\Interfaces;

interface SerieRepositoryInterface
{
    public function findByDocumentType(int $documentType): ?array;
}

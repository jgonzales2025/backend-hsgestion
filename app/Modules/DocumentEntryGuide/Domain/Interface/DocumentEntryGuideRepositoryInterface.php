<?php

namespace App\Modules\DocumentEntryGuide\Domain\Interface;

use App\Modules\DocumentEntryGuide\Domain\Entities\DocumentEntryGuide;

interface DocumentEntryGuideRepositoryInterface
{
    public function create(DocumentEntryGuide $documentEntryGuide);

    public function findById(int $id): array;
    public function findByIdObj(int $id): ?DocumentEntryGuide;
    public function findAll(): array;
    public function deleteByEntryGuideId(int $id): void;
}

<?php
namespace App\Modules\DocumentEntryGuide\Domain\Interface;

use App\Modules\DocumentEntryGuide\Domain\Entities\DocumentEntryGuide;

interface DocumentEntryGuideRepositoryInterface
{
    public function create(DocumentEntryGuide $documentEntryGuide);

    public function findById(int $id): array;
    public function findAll(): array;
}
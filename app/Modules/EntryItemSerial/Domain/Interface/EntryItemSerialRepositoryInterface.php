<?php

namespace App\Modules\EntryItemSerial\Domain\Interface;

use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryItemSerial\Domain\Entities\EntryItemSerial;

interface EntryItemSerialRepositoryInterface{

    public function save(EntryItemSerial $entryItemSerial):?EntryItemSerial;
    public function findSerialsByEntryGuideId(int $entryGuideId): array;
    public function deleteByIdEntryItemSerial(int $id): void;
    public function findBySerial(string $serial): ?EntryItemSerial;
    public function findSerialByArticleId(int $articleId, int $branch_id, ?bool $updated , ?string $serial = null): ?array;
    public function findBranchBySerial(string $serial): ?array;
    public function findSerialInDatabase(string $serial): ?bool;
    public function findEntryGuideBySerial(string $serial): ?EntryGuide;
    public function updateStatusBySerial(string $serial, int $status): void;
}

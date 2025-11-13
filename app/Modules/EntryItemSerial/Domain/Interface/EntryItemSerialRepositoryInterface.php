<?php

namespace App\Modules\EntryItemSerial\Domain\Interface;

use App\Modules\EntryItemSerial\Domain\Entities\EntryItemSerial;

interface EntryItemSerialRepositoryInterface{

    public function save(EntryItemSerial $entryItemSerial):?EntryItemSerial;
    public function findSerialsByEntryGuideId(int $entryGuideId): array;
    public function deleteByIdEntryItemSerial(int $id): void;
    public function findBySerial(string $serial): ?EntryItemSerial;
}

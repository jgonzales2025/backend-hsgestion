<?php

namespace App\Modules\EntryItemSerial\Application\UseCases;

use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\EntryItemSerial\Application\DTOS\EntryItemSerialDTO;
use App\Modules\EntryItemSerial\Domain\Entities\EntryItemSerial;

readonly class CreateEntryItemSerialUseCase{
    public function __construct(
        private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepositoryInterface
    ){}

    public function execute(EntryItemSerialDTO $entryItemSerial):?EntryItemSerial{
        $entryItemSerial = new EntryItemSerial(
            id: 0,
            entry_guide:$entryItemSerial->entry_guide,
            article:$entryItemSerial->article,
            serial:$entryItemSerial->serial,
        );
        return $this->entryItemSerialRepositoryInterface->save($entryItemSerial);
    }
}

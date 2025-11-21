<?php

namespace App\Modules\EntryItemSerial\Application\UseCases;

use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\EntryItemSerial\Application\DTOS\EntryItemSerialDTO;
use App\Modules\EntryItemSerial\Domain\Entities\EntryItemSerial;

readonly class CreateEntryItemSerialUseCase{
    public function __construct(
        private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepositoryInterface
    ){}

    public function execute(EntryItemSerialDTO $entryItemSerialDTO):?EntryItemSerial{
        $entryItemSerial = new EntryItemSerial(
            id: 0,
            entry_guide:$entryItemSerialDTO->entry_guide,
            article:$entryItemSerialDTO->article,
            serial:$entryItemSerialDTO->serial,
            branch_id:$entryItemSerialDTO->branch_id,
        );
        return $this->entryItemSerialRepositoryInterface->save($entryItemSerial);
    }
}

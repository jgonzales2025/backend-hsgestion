<?php

namespace App\Modules\EntryItemSerial\Domain\Interface;

use App\Modules\EntryItemSerial\Domain\Entities\EntryItemSerial;

interface EntryItemSerialRepositoryInterface{

    public function save(EntryItemSerial $entryItemSerial):?EntryItemSerial;
}

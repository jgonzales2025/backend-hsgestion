<?php
namespace App\Modules\RecordType\Domain\Interfaces;
use  App\Modules\RecordType\Domain\Entities\RecordType;

interface RecordTypeRepositoryInterface {
    public function findAllRecordTypes(): array;
    public function save(RecordType $RecordTytpe):?RecordType;
    public function findById(int $id):?RecordType;
    public function update(RecordType $RecordTytpe) :void;
}
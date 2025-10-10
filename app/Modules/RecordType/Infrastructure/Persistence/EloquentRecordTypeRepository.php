<?php
namespace App\Modules\RecordType\Infrastructure\Persistence;

use App\Modules\RecordType\Domain\Entities\RecordType;
use App\Modules\RecordType\Domain\Interfaces\RecordTypeRepositoryInterface;
use App\Modules\RecordType\Infrastructure\Models\EloquentRecordType;


class EloquentRecordTypeRepository implements RecordTypeRepositoryInterface{

    public function findAllRecordTypes(): array{
       $recordTypes = EloquentRecordType::all();

       if ($recordTypes->isEmpty()) {
         return [];
       }

       return $recordTypes->map(function ($recordType) {
          return new RecordType(
            id:$recordType->id,
            name:$recordType->name,
            abbreviation:$recordType->abbreviation,
            status:$recordType->status
          );
       })->toArray();
    }
    public function save(RecordType $recordType) :?RecordType{

         $eloquentRecordType = EloquentRecordType::create([
            'name' => $recordType->getName(),
            'abbreviation' => $recordType->getAbbreviation(),
            'status' => $recordType->getStatus()
         ]);

         return new RecordType(
            id:$eloquentRecordType->id,
            name:$eloquentRecordType->name,
            abbreviation:$eloquentRecordType->abbreviation,
            status:$eloquentRecordType->status
         );     
    }
     public function findById(int $id): ?RecordType
    {
        $recordType = EloquentRecordType::with('customerDocumentType')->find($id);
        if (!$recordType) {
            return null;
        }
        return new RecordType(
            id: $recordType->id,
            name: $recordType->name,
            abbreviation: $recordType->abbreviation,
            status: $recordType->status,
        );
    }

    public function update(RecordType $recordType): void
    {
        $eloquentRecordType = EloquentRecordType::find($recordType->getId());

        if (!$eloquentRecordType) {
            throw new \Exception("no encontrado");
        }

        $eloquentRecordType->update([
            'name' => $recordType->getName(),
             'abbreviation' => $recordType->getAbbreviation(),
            'status' => $recordType->getStatus(),

        ]);
    }
}
<?php
namespace App\Modules\RecordType\Application\UseCases;

use App\Modules\RecordType\Application\DTOs\RecordTypeDTO;
use App\Modules\RecordType\Domain\Entities\RecordType;
use App\Modules\RecordType\Domain\Interfaces\RecordTypeRepositoryInterface;

class CreateRecordTypeUseCase{
     
    private recordTypeRepositoryInterface $recordTypeRepository;
    public function __construct(RecordTypeRepositoryInterface $recordTypeRepository){
        $this -> recordTypeRepository = $recordTypeRepository;

    }
    public function execute(RecordTypeDTO $recordTypeDTO){
        $recordType = new RecordType(
            id: 0,
            name: $recordTypeDTO->name,
            abbreviation: $recordTypeDTO->abbreviation,
            status:$recordTypeDTO->status
        );
        
       return $this->recordTypeRepository->save($recordType);
    }
}
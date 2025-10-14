<?php 
namespace App\Modules\RecordType\Application\UseCases;

use App\Modules\RecordType\Application\DTOs\RecordTypeDTO;
use App\Modules\RecordType\Domain\Entities\RecordType;
use App\Modules\RecordType\Domain\Interfaces\RecordTypeRepositoryInterface;

class UpdateRecordTypeUseCase{
    
    private RecordTypeRepositoryInterface $recordTypeRepository;
    public function __construct(RecordTypeRepositoryInterface $recordTypeRepository){
        $this->recordTypeRepository = $recordTypeRepository;
    }
    public function execute(int $id , RecordTypeDTO $recordTypeDTO){
            $existingRecordType = $this->recordTypeRepository->findById($id);
            if (!$existingRecordType) {
                return null;
            }
            $recordType = new RecordType(
                id:$id,
                name: $recordTypeDTO->name,
                abbreviation: $recordTypeDTO->abbreviation,
                status:$recordTypeDTO->status
            );

            $this->recordTypeRepository->update($recordType);
        }
}
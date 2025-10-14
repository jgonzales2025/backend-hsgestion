<?php
namespace App\Modules\RecordType\Application\UseCases;

use App\Modules\RecordType\Domain\Interfaces\RecordTypeRepositoryInterface;


class FindByIdRecordTypeUseCase {
    private RecordTypeRepositoryInterface $recordTypeRepository;

    public function __construct(RecordTypeRepositoryInterface $recordTypeRepository){
        
        $this->recordTypeRepository = $recordTypeRepository;

    }
    public function execute(int $id){
        return $this->recordTypeRepository->findById($id);
    }
    
}
<?php
namespace App\Modules\RecordType\Application\UseCases;

use App\Modules\RecordType\Domain\Interfaces\RecordTypeRepositoryInterface;

class  FindAllRecordTypesUserCases {
    private recordTypeRepositoryInterface $recordTypeRepository;
    public function __construct(RecordTypeRepositoryInterface $recordTypeRepository){
        $this->recordTypeRepository = $recordTypeRepository;

    }
    public function execute(){
        return $this->recordTypeRepository->findAllRecordTypes();
    }
}
<?php

namespace App\Modules\ReferenceCode\Application\UseCase;

use App\Modules\ReferenceCode\Infrastructure\Persistence\EloquentReferenceCodeRepository;

class FindAllReferenceCodeUseCase{

    private EloquentReferenceCodeRepository $eloquentReferenceCodeRepository;


    public function __construct(EloquentReferenceCodeRepository $eloquentReferenceCodeRepository){
       $this->eloquentReferenceCodeRepository = $eloquentReferenceCodeRepository;
    }
    public function execute(){
         return $this->eloquentReferenceCodeRepository->findAllReferenceCode();
    }
}
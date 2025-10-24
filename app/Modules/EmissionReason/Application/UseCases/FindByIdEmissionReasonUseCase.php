<?php

namespace App\Modules\EmissionReason\Application\UseCases;

use App\Modules\EmissionReason\Domain\Entities\EmissionReason;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;

class FindByIdEmissionReasonUseCase{
    public function __construct(private readonly EmissionReasonRepositoryInterface $emissionReasonRepositoryInterface){
       
    }
    public function execute($id):?EmissionReason{
       return $this->emissionReasonRepositoryInterface->findById($id);
    }
}
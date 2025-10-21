<?php

namespace App\Modules\ReferenceCode\Application\UseCase;

use App\Modules\ReferenceCode\Application\DTOs\ReferenceCodeDTO;
use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;
use App\Modules\ReferenceCode\Domain\Interfaces\ReferenceCodeRepositoryInterface;

class CreateReferenceCodeUseCase
{
    public function __construct(private readonly ReferenceCodeRepositoryInterface $referenceCodeRepository){}

    public function execute($id,ReferenceCodeDTO $referenceCodeDTO)
    {

        $referenceCodes = new ReferenceCode(
            id: $referenceCodeDTO->id,
            ref_code: $referenceCodeDTO->ref_code,
            article_id:$id,
            status: $referenceCodeDTO->status
        );

        return $this->referenceCodeRepository->save($id,$referenceCodes);
    }
}
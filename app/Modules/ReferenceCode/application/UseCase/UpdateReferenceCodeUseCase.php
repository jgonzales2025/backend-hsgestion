<?php
namespace App\Modules\ReferenceCode\Application\UseCase;

use App\Modules\ReferenceCode\Application\DTOs\ReferenceCodeDTO;
use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;
use App\Modules\ReferenceCode\Infrastructure\Persistence\EloquentReferenceCodeRepository;

class UpdateReferenceCodeUseCase{

    private EloquentReferenceCodeRepository $eloquentReferenceCodeRepository;

     public function execute(int $id, ReferenceCodeDTO $referenceCodeDTO): ?ReferenceCode
    {
        $updatedReferenceCode = new ReferenceCode(
            $id,
            $referenceCodeDTO->refCode,
            $referenceCodeDTO->articleId,
            $referenceCodeDTO->dateAt,
            $referenceCodeDTO->status
        );

        return $this->eloquentReferenceCodeRepository->update($updatedReferenceCode);
    }
}
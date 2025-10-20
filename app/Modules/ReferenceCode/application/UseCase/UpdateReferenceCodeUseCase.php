<?php
namespace App\Modules\ReferenceCode\Application\UseCase;

use App\Modules\ReferenceCode\Application\DTOs\ReferenceCodeDTO;
use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;
use App\Modules\ReferenceCode\Domain\Interfaces\ReferenceCodeRepositoryInterface;
use App\Modules\ReferenceCode\Infrastructure\Persistence\EloquentReferenceCodeRepository;

class UpdateReferenceCodeUseCase{

    public function __construct(private readonly ReferenceCodeRepositoryInterface $referenceCodeRepository){}

     public function execute(int $id, ReferenceCodeDTO $referenceCodeDTO): void
    {
        $updatedReferenceCode = new ReferenceCode(
            $id,
            $referenceCodeDTO->ref_code,
            $referenceCodeDTO->article_id,
             now(),
            $referenceCodeDTO->status
        );

         $this->referenceCodeRepository->update($updatedReferenceCode);
    }
}
<?php

namespace App\Modules\ReferenceCode\Application\UseCase;

use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\ReferenceCode\Domain\Interfaces\ReferenceCodeRepositoryInterface;

class FindByIdReferenceCodeUseCase
{
   public function __construct(private readonly ReferenceCodeRepositoryInterface $referenceCodeRepository)
   {
   }

   public function execute($id): array
   {

      return $this->referenceCodeRepository->findById($id);

   }
}
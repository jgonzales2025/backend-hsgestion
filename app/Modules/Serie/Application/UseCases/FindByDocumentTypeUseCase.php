<?php

namespace App\Modules\Serie\Application\UseCases;

use App\Modules\Serie\Domain\Entities\Serie;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;

readonly class FindByDocumentTypeUseCase
{
    public function __construct(private readonly SerieRepositoryInterface $serieRepository){}

    public function execute(int $documentType, int $branch_id): ?Serie
    {
        return $this->serieRepository->findByDocumentType($documentType, $branch_id);
    }
}

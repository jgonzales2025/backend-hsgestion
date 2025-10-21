<?php

namespace App\Modules\Serie\Application\UseCases;

use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;

readonly class FindByDocumentTypeUseCase
{
    public function __construct(private readonly SerieRepositoryInterface $serieRepository){}

    public function execute(int $documentType): ?array
    {
        return $this->serieRepository->findByDocumentType($documentType);
    }
}

<?php

namespace App\Modules\EmissionReason\Application\UseCases;

use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;

readonly class FindAllEmissionReasonUseCase
{
    public function __construct(private readonly EmissionReasonRepositoryInterface $emissionReasonRepository){}

    public function execute(): array
    {
        return $this->emissionReasonRepository->findAll();
    }
}

<?php

namespace App\Modules\IngressReason\Application\UseCases;

use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;

readonly class FindAllIngressReasonUseCase
{
    public function __construct(private readonly IngressReasonRepositoryInterface $ingressReasonRepository){}

    public function execute(): array
    {
        return $this->ingressReasonRepository->findAll();
    }
}

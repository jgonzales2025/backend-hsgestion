<?php

namespace App\Modules\IngressReason\Application\UseCases;

use App\Modules\IngressReason\Domain\Entities\IngressReason;
use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;

readonly class FindByIdIngressReasonUseCase
{
    public function __construct(private readonly IngressReasonRepositoryInterface $ingressReasonRepository){}

    public function execute(int $id): ?IngressReason
    {
        return $this->ingressReasonRepository->findById($id);
    }
}

<?php

namespace App\Modules\TransportCompany\Application\UseCases;

use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;

readonly class FindAllPublicTransportUseCase
{
    public function __construct(private readonly TransportCompanyRepositoryInterface $transportCompanyRepository){}

    public function execute(): array
    {
        return $this->transportCompanyRepository->findAllPublicTransport();
    }
}

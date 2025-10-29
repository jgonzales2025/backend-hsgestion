<?php

namespace App\Modules\TransportCompany\Application\UseCases;

use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;

readonly class FindPrivateTransportUseCase
{
    public function __construct(private readonly TransportCompanyRepositoryInterface $transportCompanyRepository){}

    public function execute(): ?TransportCompany
    {
        return $this->transportCompanyRepository->findPrivateTransport();
    }
}

<?php

namespace App\Modules\TransportCompany\Application\UseCases;

use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;

class FindCompanyTransport
{
    public function __construct(private readonly TransportCompanyRepositoryInterface $transportCompanyRepository)
    {
    }
    public function execute(string $documentNumber): ?TransportCompany
    {
        return $this->transportCompanyRepository->findTransporCompanyByDocumentNumber($documentNumber);
    }
}
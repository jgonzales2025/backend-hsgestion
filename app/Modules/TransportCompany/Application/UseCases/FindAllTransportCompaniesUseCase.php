<?php

namespace App\Modules\TransportCompany\Application\UseCases;

use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;

class FindAllTransportCompaniesUseCase
{
    private transportCompanyRepositoryInterface $transportCompanyRepository;

    public function __construct(TransportCompanyRepositoryInterface $transportCompanyRepository)
    {
        $this->transportCompanyRepository = $transportCompanyRepository;
    }

    public function execute(?string $description, ?int $status)
    {
        return $this->transportCompanyRepository->findAll($description, $status);
    }
}

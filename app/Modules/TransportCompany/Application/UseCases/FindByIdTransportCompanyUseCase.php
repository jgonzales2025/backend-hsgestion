<?php

namespace App\Modules\TransportCompany\Application\UseCases;

use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;

class FindByIdTransportCompanyUseCase
{
    private transportCompanyRepositoryInterface $transportCompanyRepository;

    public function __construct(TransportCompanyRepositoryInterface $transportCompanyRepository)
    {
        $this->transportCompanyRepository = $transportCompanyRepository;
    }

    public function execute(int $id): ?TransportCompany
    {
        return $this->transportCompanyRepository->findById($id);
    }
}

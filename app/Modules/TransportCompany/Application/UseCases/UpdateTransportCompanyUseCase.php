<?php

namespace App\Modules\TransportCompany\Application\UseCases;

use App\Modules\TransportCompany\Application\DTOs\TransportCompanyDTO;
use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;

class UpdateTransportCompanyUseCase
{
    private transportCompanyRepositoryInterface $transportCompanyRepository;

    public function __construct(TransportCompanyRepositoryInterface $transportCompanyRepository)
    {
        $this->transportCompanyRepository = $transportCompanyRepository;
    }

    public function execute(TransportCompany $transportCompany, TransportCompanyDTO $transportCompanyDTO): ?TransportCompany
    {
        $transport = new TransportCompany(
            id: $transportCompany->getId(),
            ruc: $transportCompanyDTO->ruc,
            company_name: $transportCompanyDTO->company_name,
            address: $transportCompanyDTO->address,
            nro_reg_mtc: $transportCompanyDTO->nro_reg_mtc,
            status: $transportCompanyDTO->status,
        );

        return $this->transportCompanyRepository->update($transport);
    }
}

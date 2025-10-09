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

    public function execute(int $id, TransportCompanyDTO $transportCompanyDTO)
    {
        $existingTransport = $this->transportCompanyRepository->findById($id);

        if (!$existingTransport) {
            return null;
        }

        $transport = new TransportCompany(
            id: $id,
            ruc: $transportCompanyDTO->ruc,
            company_name: $transportCompanyDTO->company_name,
            address: $transportCompanyDTO->address,
            nro_reg_mtc: $transportCompanyDTO->nro_reg_mtc,
            status: $transportCompanyDTO->status,
        );

        $this->transportCompanyRepository->update($transport);
    }
}

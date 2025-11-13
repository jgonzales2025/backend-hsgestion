<?php

namespace App\Modules\TransportCompany\Application\UseCases;

use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;

class UpdateStatusTransportCompanyUseCase
{
    private $transportCompanyRepository;

    public function __construct(TransportCompanyRepositoryInterface $transportCompanyRepository)
    {
        $this->transportCompanyRepository = $transportCompanyRepository;
    }

    public function execute(int $transportCompanyId, int $status): void
    {
        $this->transportCompanyRepository->updateStatus($transportCompanyId, $status);
    }
}

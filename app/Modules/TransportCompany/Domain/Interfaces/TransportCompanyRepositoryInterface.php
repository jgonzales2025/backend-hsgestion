<?php

namespace App\Modules\TransportCompany\Domain\Interfaces;

use App\Modules\TransportCompany\Domain\Entities\TransportCompany;

interface TransportCompanyRepositoryInterface
{
    public function findAll(): array;

    public function save(TransportCompany $transportCompany): ?TransportCompany;

    public function findById(int $id): ?TransportCompany;

    public function update(TransportCompany $transportCompany): ?TransportCompany;

    public function findPrivateTransport(): ?TransportCompany;

    public function findAllPublicTransport(): array;
}

<?php

namespace App\Modules\TransportCompany\Infrastructure\Persistence;

use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Modules\TransportCompany\Infrastructure\Models\EloquentTransportCompany;

class EloquentTransportCompanyRepository implements TransportCompanyRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentTransportCompanies = EloquentTransportCompany::all()->sortByDesc('created_at');

        if ($eloquentTransportCompanies->isEmpty()) {
            return [];
        }

        return $eloquentTransportCompanies->map(function ($eloquentTransportCompany) {
           return new TransportCompany(
               id: $eloquentTransportCompany->id,
               ruc: $eloquentTransportCompany->ruc,
               company_name: $eloquentTransportCompany->company_name,
               address: $eloquentTransportCompany->address,
               nro_reg_mtc: $eloquentTransportCompany->nro_reg_mtc,
               status: $eloquentTransportCompany->status,
           ) ;
        })->toArray();
    }

    public function save(TransportCompany $transportCompany): TransportCompany
    {
        $eloquentTransport = EloquentTransportCompany::create([
            'ruc' => $transportCompany->getRuc(),
            'company_name' => $transportCompany->getCompanyName(),
            'address' => $transportCompany->getAddress(),
            'nro_reg_mtc' => $transportCompany->getNroRegMtc(),
            'status' => $transportCompany->getStatus(),
        ]);

        return new TransportCompany(
            id: $eloquentTransport->id,
            ruc: $eloquentTransport->ruc,
            company_name: $eloquentTransport->company_name,
            address: $eloquentTransport->address,
            nro_reg_mtc: $eloquentTransport->nro_reg_mtc,
            status: $eloquentTransport->status,
        );
    }

    public function findById(int $id): ?TransportCompany
    {
        $eloquentTransport = EloquentTransportCompany::find($id);

        if (!$eloquentTransport) {
            return null;
        }

        return new TransportCompany(
            id: $eloquentTransport->id,
            ruc: $eloquentTransport->ruc,
            company_name: $eloquentTransport->company_name,
            address: $eloquentTransport->address,
            nro_reg_mtc: $eloquentTransport->nro_reg_mtc,
            status: $eloquentTransport->status,
        );
    }

    public function update(TransportCompany $transportCompany): void
    {
        $eloquentTransport = EloquentTransportCompany::find($transportCompany->getId());

        if (!$eloquentTransport) {
            throw new \Exception("Transporte no encontrado");
        }

        $eloquentTransport->update([
            'ruc' => $transportCompany->getRuc(),
            'company_name' => $transportCompany->getCompanyName(),
            'address' => $transportCompany->getAddress(),
            'nro_reg_mtc' => $transportCompany->getNroRegMtc(),
            'status' => $transportCompany->getStatus(),
        ]);
    }
}

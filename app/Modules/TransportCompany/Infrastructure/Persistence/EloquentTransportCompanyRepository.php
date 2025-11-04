<?php

namespace App\Modules\TransportCompany\Infrastructure\Persistence;

use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Modules\TransportCompany\Infrastructure\Models\EloquentTransportCompany;

class EloquentTransportCompanyRepository implements TransportCompanyRepositoryInterface
{

    public function findAll(?string $description): array
    {
        $eloquentTransportCompanies = EloquentTransportCompany::when($description, function ($query, $description) {
            return $query->where('company_name', 'like', "%{$description}%")
                ->orWhere('ruc', 'like', "%{$description}%");
        })
            ->orderByDesc('created_at')
            ->get();

        if ($eloquentTransportCompanies->isEmpty()) {
            return [];
        }

        return $eloquentTransportCompanies->map(fn($company) => $this->mapToEntity($company))->toArray();
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

        return $this->mapToEntity($eloquentTransport);
    }

    public function findById(int $id): ?TransportCompany
    {
        $eloquentTransport = EloquentTransportCompany::find($id);

        if (!$eloquentTransport) {
            return null;
        }

        return $this->mapToEntity($eloquentTransport);
    }

    public function update(TransportCompany $transportCompany): ?TransportCompany
    {
        $eloquentTransport = EloquentTransportCompany::find($transportCompany->getId());

        $eloquentTransport->update([
            'ruc' => $transportCompany->getRuc(),
            'company_name' => $transportCompany->getCompanyName(),
            'address' => $transportCompany->getAddress(),
            'nro_reg_mtc' => $transportCompany->getNroRegMtc(),
            'status' => $transportCompany->getStatus(),
        ]);

        return $this->mapToEntity($eloquentTransport);
    }

    public function findPrivateTransport(): ?TransportCompany
    {
        $companyId = request()->get('company_id');

        $transportCompany = EloquentTransportCompany::where('id', $companyId)->first();

        return $this->mapToEntity($transportCompany);
    }

    public function findAllPublicTransport(): array
    {
        $transportCompanies = EloquentTransportCompany::where('st_private', 0)->get();

        return $transportCompanies->map(fn($company) => $this->mapToEntity($company))->toArray();
    }

    private function mapToEntity($eloquentTransportCompany): TransportCompany
    {
        return new TransportCompany(
            id: $eloquentTransportCompany->id,
            ruc: $eloquentTransportCompany->ruc,
            company_name: $eloquentTransportCompany->company_name,
            address: $eloquentTransportCompany->address,
            nro_reg_mtc: $eloquentTransportCompany->nro_reg_mtc,
            status: $eloquentTransportCompany->status,
        );
    }
}

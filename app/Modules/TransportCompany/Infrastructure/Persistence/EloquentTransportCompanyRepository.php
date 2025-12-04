<?php

namespace App\Modules\TransportCompany\Infrastructure\Persistence;

use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Modules\TransportCompany\Infrastructure\Models\EloquentTransportCompany;

class EloquentTransportCompanyRepository implements TransportCompanyRepositoryInterface
{

    public function findAll(?string $description, ?int $status)
    {
        $eloquentTransportCompanies = EloquentTransportCompany::when($description, function ($query, $description) {
            return $query->where('company_name', 'like', "%{$description}%")
                ->orWhere('ruc', 'like', "%{$description}%");
        })
        ->when($status !== null, fn($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(10);

        $eloquentTransportCompanies->getCollection()->transform(fn($company) => $this->mapToEntity($company));

        return $eloquentTransportCompanies;
    }

    public function save(TransportCompany $transportCompany): TransportCompany
    {
        $eloquentTransport = EloquentTransportCompany::create([
            'ruc' => $transportCompany->getRuc(),
            'company_name' => $transportCompany->getCompanyName(),
            'address' => $transportCompany->getAddress(),
            'nro_reg_mtc' => $transportCompany->getNroRegMtc()
        ]);
        $eloquentTransport->refresh();

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
            'nro_reg_mtc' => $transportCompany->getNroRegMtc()
        ]);

        return $this->mapToEntity($eloquentTransport);
    }

    public function findPrivateTransport(): ?TransportCompany
    {
        $companyId = request()->get('company_id');

        $transportCompany = EloquentTransportCompany::where('id', $companyId)->first();

        return $this->mapToEntity($transportCompany);
    }

    public function findAllPublicTransport(?string $description): array
    {
        
        $transportCompanies = EloquentTransportCompany::where('st_private', 0)
        ->when($description, function ($query, $description) {
        $query->where(function ($q) use ($description) {
            $q->where('company_name', 'like', "%{$description}%")
              ->orWhere('ruc', 'like', "%{$description}%");
        });
    })
        ->get();

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
    public function saveTransportCompanyBySunatApi(TransportCompany $customer): ?TransportCompany{
        $eloquentTransport = EloquentTransportCompany::create([
            'ruc' => $customer->getRuc(),
            'company_name' => $customer->getCompanyName(),
            'address' => $customer->getAddress(),
            'nro_reg_mtc' => $customer->getNroRegMtc()
        ]);

        return $this->mapToEntity($eloquentTransport);
    }
    public function findTransporCompanyByDocumentNumber(string $documentNumber): ?TransportCompany
    {
        $transportCompany = EloquentTransportCompany::where('ruc', $documentNumber)->first();

        if (!$transportCompany) {
            return null;
        }

        return $this->mapToEntity($transportCompany);
    }

    public function updateStatus(int $transportCompanyId, int $status): void
    {
        EloquentTransportCompany::where('id', $transportCompanyId)->update(['status' => $status]);
    }
}

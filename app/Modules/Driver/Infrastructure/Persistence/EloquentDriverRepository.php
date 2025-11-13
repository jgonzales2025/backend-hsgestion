<?php

namespace App\Modules\Driver\Infrastructure\Persistence;

use App\Modules\Driver\Domain\Entities\Driver;
use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;
use App\Modules\Driver\Infrastructure\Models\EloquentDriver;

class EloquentDriverRepository implements DriverRepositoryInterface
{

    public function findAllDrivers(?string $description): array
    {
        $drivers = EloquentDriver::with('customerDocumentType')
            ->when($description, function ($query, $description) {
                return $query->where('name', 'like', "%{$description}%")
                    ->orWhere('pat_surname', 'like', "%{$description}%")
                    ->orWhere('mat_surname', 'like', "%{$description}%")
                    ->orWhere('doc_number', 'like', "%{$description}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($drivers->isEmpty()) {
            return [];
        }

        return $drivers->map(function ($driver) {
            return new Driver(
                id: $driver->id,
                customer_document_type_id: $driver->customer_document_type_id,
                doc_number: $driver->doc_number,
                name: $driver->name,
                pat_surname: $driver->pat_surname,
                mat_surname: $driver->mat_surname,
                status: $driver->status,
                license: $driver->license,
                document_type_name: $driver->customerDocumentType?->abbreviation
            );
        })->toArray();

    }

    public function save(Driver $driver): ?Driver
    {
        $eloquentDriver = EloquentDriver::create([
            'customer_document_type_id' => $driver->getCustomerDocumentTypeId(),
            'doc_number' => $driver->getDocNumber(),
            'name' => $driver->getName(),
            'pat_surname' => $driver->getPatSurname(),
            'mat_surname' => $driver->getMatSurname(),
            'license' => $driver->getLicense()
        ]);
        $eloquentDriver->refresh();

        return new Driver(
            id: $eloquentDriver->id,
            customer_document_type_id: $eloquentDriver->customer_document_type_id,
            doc_number: $eloquentDriver->doc_number,
            name: $eloquentDriver->name,
            pat_surname: $eloquentDriver->pat_surname,
            mat_surname: $eloquentDriver->mat_surname,
            status: $eloquentDriver->status,
            license: $eloquentDriver->license,
            document_type_name: $eloquentDriver->customerDocumentType?->abbreviation,
        );
    }

    public function findById(int $id): ?Driver
    {
        $driver = EloquentDriver::with('customerDocumentType')->find($id);
        if (!$driver) {
            return null;
        }
        return new Driver(
            id: $driver->id,
            customer_document_type_id: $driver->customer_document_type_id,
            doc_number: $driver->doc_number,
            name: $driver->name,
            pat_surname: $driver->pat_surname,
            mat_surname: $driver->mat_surname,
            status: $driver->status,
            license: $driver->license,
            document_type_name: $driver->customerDocumentType->abbreviation
        );
    }

    public function update(Driver $driver): void
    {
        $eloquentDriver = EloquentDriver::find($driver->getId());

        if (!$eloquentDriver) {
            throw new \Exception("Conductor no encontrado");
        }

        $eloquentDriver->update([
            'customer_document_type_id' => $driver->getCustomerDocumentTypeId(),
            'doc_number' => $driver->getDocNumber(),
            'name' => $driver->getName(),
            'pat_surname' => $driver->getPatSurname(),
            'mat_surname' => $driver->getMatSurname(),
            'license' => $driver->getLicense(),
        ]);
    }
    function findDriverByDocumentNumber(string $documentNumber): ?Driver
    {
        $driver = EloquentDriver::where('doc_number', $documentNumber)->first();
        if (!$driver) {
            return null;
        }
        return new Driver(
            id: $driver->id,
            customer_document_type_id: $driver->customer_document_type_id,
            doc_number: $driver->doc_number,
            name: $driver->name,
            pat_surname: $driver->pat_surname,
            mat_surname: $driver->mat_surname,
            status: $driver->status,
            license: $driver->license,
            document_type_name: $driver->customerDocumentType->abbreviation
        );
    }

    public function updateStatus(int $driverId, int $status): void
    {
        EloquentDriver::where('id', $driverId)->update(['status' => $status]);
    }
}

<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;

class EloquentDIspatchNoteRepository implements DispatchNotesRepositoryInterface
{
    public function findAll(): array
    {
        $dispatchNote = EloquentDispatchNote::with(     
            'measurementUnit',
                       'category',
                       'currencyType',
                       'company',
                       'drivers',
        );

        return $dispatchNote->map(function ($dispatch) {
                return  new DispatchNote(
                id: $dispatch->id,
                cia: $dispatch->cia->toDomain($dispatch->cia),
                branch: $dispatch->brand->toDomain($dispatch->branch),
                serie: $dispatch->serie,
                correlativo: $dispatch->correlativo,
                date: $dispatch->date,
                emission_reason: $dispatch->emission_reason->toDomain($dispatch->emission_reason),
                description: $dispatch->description,
                destination_branch: $dispatch->destination_branch,
                destination_address_customer: $dispatch->destination_address_customer,
                transport: $dispatch->transport->toDomain->toDomain($dispatch->transport),
                observations: $dispatch->observations,
                num_orden_compra: $dispatch->num_orden_compra,
                doc_referencia: $dispatch->doc_referencia,
                num_referencia: $dispatch->num_referencia,
                serie_referencia: $dispatch->serie_referencia,
                date_referencia: $dispatch->date_referencia,
                status: $dispatch->status,
                conductor: $dispatch->conductor,
                license_plate: $dispatch->license_plate,
                total_weight: $dispatch->total_weight,
                transfer_type: $dispatch->transfer_type,
                vehicle_type: $dispatch->vehicle_type,
            );
        });
    }
}
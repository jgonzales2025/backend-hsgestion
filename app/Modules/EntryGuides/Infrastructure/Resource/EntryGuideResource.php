<?php

namespace App\Modules\EntryGuides\Infrastructure\Resource;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EntryGuideResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'serie' => $this->resource->getSerie(),
            'correlativo' => $this->resource->getCorrelativo(),

            'date' => Carbon::createFromFormat('d/m/Y', $this->resource->getDate())->format('Y-m-d'),
            'observations' => $this->resource->getObservations(),

            'reference_serie' => $this->resource->getReferenceSerie(),
            'reference_correlative' => $this->resource->getReferenceCorrelative(),

            'status' => $this->resource->getStatus() ? 'Activo' : 'Inactivo',
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
            ],
            'customer' => [
                'id' => $this->resource->getCustomer()->getId(),
                'name' => $this->resource->getCustomer()->getCompanyName() ??
                    trim($this->resource->getCustomer()->getName() . ' ' .
                        $this->resource->getCustomer()->getLastname() . ' ' .
                        $this->resource->getCustomer()->getSecondLastname()),
                'document_number' => $this->resource->getCustomer()->getDocumentNumber() ?? $this->getCustomer()->getLastname(),

            ],
            'ingress_reason' => [
                'id' => $this->resource->getIngressReason()->getId(),
                'name' => $this->resource->getIngressReason()->getDescription(),
                'status' => ($this->resource->getIngressReason()->getStatus()) ? 'Activo' : 'Inactivo',
            ],
            'subtotal' => $this->resource?->getSubtotal(),
            'total_descuento' => $this->resource?->getTotalDescuento(),
            'total' => $this->resource?->getTotal(),
            'update_price' => $this->resource?->getUpdatePrice(),
            'entry_igv' => $this->resource?->getEntryIgv(),
            'currency' => [
                'id' => $this->resource->getCurrency()->getId(),
                'name' => $this->resource->getCurrency()->getName(),
            ],
            'includ_igv' => $this->resource?->getIncludIgv(),
            'reference_document_id' => $this->resource?->getReferenceDocument(),
            'nc_document_id' => $this->resource?->getNcDocumentId(),
            'nc_reference_serie' => $this->resource?->getNcReferenceSerie(),
            'nc_reference_correlative' => $this->resource?->getNcReferenceCorrelative(),
            'estado' => $this->statusDate(),
        ];
    }

    private function statusDate()
    {
        $fecha = Carbon::createFromFormat(
            'd/m/Y',
            $this->resource->getDate()
        )->format('Y-m-d');

        $result = DB::select(
            'CALL sp_bloqueo_diario(?, ?)',
            [$fecha, 1]
        );

        return $result[0]->bloqueado;
    }
}

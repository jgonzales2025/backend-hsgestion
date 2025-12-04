<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class SelectProcedureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'cia'         => $this->resource['cia'] ?? null,
            'fecha'       => $this->resource['fecha'] ?? null,
            'fechaU'      => $this->resource['fechaU'] ?? null,
            'cliente'     => $this->resource['cliente'] ?? null,
            'sucursal'    => $this->resource['sucursal'] ?? null,
            'tipo_pago'   => $this->resource['tipo_pago'] ?? null,
            'banco'       => $this->resource['banco'] ?? null,
            'operacion'   => $this->resource['operacion'] ?? null,
            'doc'         => $this->resource['doc'] ?? null,
            'serie'       => $this->resource['serie'] ?? null,
            'correlativo' => $this->resource['correlativo'] ?? null,
            'monto'       => (float)($this->resource['monto'] ?? 0),
        ];
    }
}

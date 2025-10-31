<?php

namespace App\Modules\DispatchNotes\Infrastructure\Resource;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DispatchNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $pdfUrl = null;

        $incluyePDF = $request->query('include_pdf', false);
        if ($incluyePDF == "true") {
            try {
                $pdf = Pdf::loadView('invoice', ['dispatchNote' => $this->resource]);
                $filename = 'dispatch_note_' . $this->resource->getId() . '.pdf';
                $path = 'pdf/' . $filename;

                // Guardar en storage
                Storage::disk('public')->put($path, $pdf->output());

                // Obtener URL pública
                $pdfUrl = asset('storage/' . $path);

            } catch (\Throwable $e) {
                \Log::error('Error generando PDF: ' . $e->getMessage());
            }
        }

        return [
            'id' => $this->resource->getId(),

            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'status' => ($this->resource->getCompany()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'status' => ($this->resource->getBranch()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            'emission_reason' => [
                'id' => $this->resource->getEmissionReason()->getId(),
                'status' => ($this->resource->getEmissionReason()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
                'name' => $this->resource->getEmissionReason()->getDescription()
            ],
            'destination_branch' => [
                'id' => $this->resource->getDestinationBranch()?->getId(),
                'status' => $this->resource->getDestinationBranch()?->getStatus(),
                'name' => $this->resource->getDestinationBranch()?->getName()
            ],
            'serie' => $this->resource->getSerie(),
            'correlativo' => $this->resource->getCorrelativo(),
            'description' => $this->resource->getDescription(),
            'destination_address_customer' => $this->resource->getDestinationAddressCustomer(),
            'transport' => [
                'id' => $this->resource->getTransport()->getId(),
                'status' => $this->resource->getTransport()->getStatus(),
                'name' => $this->resource->getTransport()?->getCompanyName()

            ],
            'observations' => $this->resource->getObservations(),
            'num_orden_compra' => $this->resource->getNumOrdenCompra(),
            'doc_referencia' => $this->resource->getDocReferencia(),
            'num_referencia' => $this->resource->getNumReferencia(),
            'date_referencia' => $this->resource->getDateReferencia(),
            'status' => $this->resource->getStatus() == "true" ? "Activo" : "Inactivo",
            'conductor' => [
                'id' => $this->resource->getConductor()?->getId(),
                'status' => $this->resource->getConductor()?->getStatus() == 1 ? 'Activo' : 'Inactivo',
                'name' => $this->resource->getConductor()?->getName()
            ],
            'license_plate' => $this->resource->getLicensePlate(),
            'total_weight' => $this->resource->getTotalWeight(),
            'transfer_type' => $this->resource->getTransferType(),
            'vehicle_type' => $this->resource->getVehicleType(),
            'document_type' => [
                'id' => $this->resource->getDocumentType()->getId(),
                'status' => ($this->resource->getDocumentType()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
                'description' => $this->resource->getDocumentType()->getDescription(),
            ],
            'destination_branch_client_id' => (function () {
                $code = EloquentCustomer::where('id', $this->resource->getdestination_branch_client())->first(); 
    
                if (!$code) {
                    return [];
                }

                return (object) [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                    'name' => $code->address[0]['address'],

                ];
            })(),

            'date' => $this->resource->getCreatedFecha(),

            'pdf_url' => $pdfUrl,
            'customer' =>(function () {
                $code = EloquentCustomer::where('id', $this->resource->getCustomerId())->first(); 
    
                if (!$code) {
                    return [];
                }

                return (object) [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                    'name' => $code->name,

                ];
            })(),

        ];
    }
}
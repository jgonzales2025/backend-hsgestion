<?php

namespace App\Modules\DispatchNotes\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Category\Infrastructure\Models\EloquentCategory;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\Driver\Infrastructure\Models\EloquentDriver;
use App\Modules\EmissionReason\Infrastructure\Models\EloquentEmissionReason;
use App\Modules\MeasurementUnit\Infrastructure\Models\EloquentMeasurementUnit;
use App\Modules\SubCategory\Infrastructure\Models\EloquentSubCategory;
use App\Modules\TransportCompany\Infrastructure\Models\EloquentTransportCompany;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentDispatchNote extends Model
{
    protected $table = 'dispatch_notes';
    protected $fillable = [
        'cia_id',
        'branch_id',
        'document_type_id',
        'reference_document_type_id',
        'serie',
        'correlativo',
        'date',
        'emission_reason_id',
        'description',
        'destination_branch_id',
        'transport_id',
        'observations',
        'num_orden_compra',
        'doc_referencia',
        'num_referencia',
        'date_referencia',
        'status',
        'stage',
        'cod_conductor',
        'license_plate',
        'total_weight',
        'transfer_type',
        'vehicle_type',
        'destination_branch_client',
        'customer_id',
        'supplier_id',
        'address_supplier_id',
        'pdf',
        'transfer_date',
        'arrival_date',
        'estado_sunat',
    ];
    public $timestamps = true;


    public function measurementUnit(): BelongsTo
    {
        return $this->belongsTo(EloquentMeasurementUnit::class, 'measurement_unit_id');
    }
    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(EloquentCategory::class, 'category_id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'cia_id');
    }
    public function conductor(): BelongsTo
    {
        return $this->belongsTo(EloquentDriver::class, 'cod_conductor');
    }
    public function transport(): BelongsTo
    {
        return $this->belongsTo(EloquentTransportCompany::class, 'transport_id');
    }
    public function emission_reason(): BelongsTo
    {
        return $this->belongsTo(EloquentEmissionReason::class, 'emission_reason_id');
    }
    public function document_type(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'document_type_id');
    }
    public function destination_branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'destination_branch_id');
    }
    public function destinationBranchClient(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomerAddress::class, 'destination_branch_client');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'supplier_id');
    }
    public function address_supplier()
    {
        return $this->belongsTo(EloquentCustomer::class, 'address_supplier_id');
    }
    public function referenceDocumentType(): BelongsTo
    {
        return $this->belongsTo(EloquentDocumentType::class, 'reference_document_type_id');
    }

    public function toDomain(EloquentDispatchNote $dispatchNote): DispatchNote
    {
        return new DispatchNote(
            id: $dispatchNote->id,
            company: $dispatchNote->company->toDomain($dispatchNote->company),
            branch: $dispatchNote->branch->toDomain($dispatchNote->branch),
            serie: $dispatchNote->serie,
            correlativo: $dispatchNote->correlativo,
            emission_reason: $dispatchNote->emission_reason->toDomain($dispatchNote->emission_reason),
            description: $dispatchNote->description,
            destination_branch: $dispatchNote->destination_branch?->toDomain($dispatchNote->destination_branch),
            transport: $dispatchNote->transport?->toDomain($dispatchNote->transport),
            observations: $dispatchNote->observations,
            num_orden_compra: $dispatchNote->num_orden_compra,
            doc_referencia: $dispatchNote->doc_referencia,
            num_referencia: $dispatchNote->num_referencia,
            date_referencia: $dispatchNote->date_referencia,
            status: $dispatchNote->status,
            conductor: $dispatchNote->conductor?->toDomain($dispatchNote->conductor),
            license_plate: $dispatchNote->license_plate,
            total_weight: $dispatchNote->total_weight,
            transfer_type: $dispatchNote->transfer_type,
            vehicle_type: $dispatchNote->vehicle_type,
            destination_branch_client: $dispatchNote->destination_branch_client,
            customer_id: $dispatchNote->customer_id,
            supplier: $dispatchNote->supplier?->toDomain($dispatchNote->supplier),
            address_supplier: $dispatchNote->address_supplier?->toDomain($dispatchNote->address_supplier),
            reference_document_type: $dispatchNote->referenceDocumentType?->toDomain($dispatchNote->referenceDocumentType),
            created_at: $dispatchNote->created_at ? $dispatchNote->created_at->format('Y-m-d H:i:s') : null,
            estado_sunat: $dispatchNote->estado_sunat
        );
    }
}

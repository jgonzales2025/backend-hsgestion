<?php

namespace App\Modules\DispatchNotes\Infrastructure\Models;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Category\Infrastructure\Models\EloquentCategory;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
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
        'serie',
        'correlativo',
        'date',
        'emission_reason_id',
        'description',
        'destination_branch_id',
        'destination_address_customer',
        'transport_id',
        'observations',
        'num_orden_compra',
        'doc_referencia',
        'num_referencia',
        'date_referencia',
        'status',
        'cod_conductor',
        'license_plate',
        'total_weight',
        'transfer_type',
        'vehicle_type',
        'document_type_id',
        'destination_branch_client',
        'customer_id',
        'supplier_id',
        'address_supplier_id',
        'pdf',  
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
    public function supplier():BelongsTo{
        return $this->belongsTo(EloquentCustomer::class, 'supplier_id');
    }
    public function address_supplier(){
        return $this->belongsTo(EloquentCustomer::class, 'address_supplier_id');
    }
}
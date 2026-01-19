<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DispatchNotes\Application\DTOs\DispatchNoteDTO;
use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\Driver\Application\UseCases\FindByIdDriverUseCase;
use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;
use App\Modules\EmissionReason\Application\UseCases\FindByIdEmissionReasonUseCase;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\TransportCompany\Application\UseCases\FindByIdTransportCompanyUseCase;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

class CreateDispatchNoteUseCase
{
  public function __construct(
    private readonly DispatchNotesRepositoryInterface $dispatchNoteRepository,
    private readonly CompanyRepositoryInterface $companyRepositoryInterface,
    private readonly BranchRepositoryInterface $branchRepository,
    private readonly SerieRepositoryInterface $serieRepositoryInterface,
    private readonly EmissionReasonRepositoryInterface $emissionReasonRepositoryInterface,
    private readonly TransportCompanyRepositoryInterface $transportCompany,
    private readonly DocumentTypeRepositoryInterface $documentTypeRepositoryInterface,
    private readonly DriverRepositoryInterface $driverRepositoryInterface,
    private readonly CustomerRepositoryInterface $customerRepositoryInterface,
    private readonly DocumentNumberGeneratorService $documentNumberGeneratorService
  ) {}

  public function execute(DispatchNoteDTO $data): DispatchNote
  {
    $lastDocumentNumber = $this->dispatchNoteRepository->getLastDocumentNumber($data->serie);
    $data->correlativo = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);

    $companyUseCase = new FindByIdCompanyUseCase($this->companyRepositoryInterface);
    $company = $companyUseCase->execute($data->cia_id);

    $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
    $branch = $branchUseCase->execute($data->branch_id);

    $emissionReasonUseCase = new FindByIdEmissionReasonUseCase($this->emissionReasonRepositoryInterface);
    $emissionReason = $emissionReasonUseCase->execute($data->emission_reason_id);

    if ($data->destination_branch_id != null) {

      $destinationUseCase = new FindByIdBranchUseCase($this->branchRepository);
      $destination = $destinationUseCase->execute($data->destination_branch_id);
    } else {
      $destination = null;
    }

    $driverUseCase = new FindByIdDriverUseCase($this->driverRepositoryInterface);
    $driver = $driverUseCase->execute($data->cod_conductor);


    $transportCompanyUseCase = new FindByIdTransportCompanyUseCase($this->transportCompany);
    $transportCompany = $transportCompanyUseCase->execute($data->transport_id);


    if ($data->supplier_id != null) {

      $supplierUseCase = new FindByIdCustomerUseCase($this->customerRepositoryInterface);
      $supplier = $supplierUseCase->execute($data->supplier_id);
    } else {
      $supplier = null;
    }
    if ($data->address_supplier_id != null) {

      $supplierUseCase = new FindByIdCustomerUseCase($this->customerRepositoryInterface);
      $supplierAddress = $supplierUseCase->execute($data->address_supplier_id);
    } else {
      $supplierAddress = null;
    }

    $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentTypeRepositoryInterface);
    $referenceDocumentType = $data->reference_document_type_id ? $documentTypeUseCase->execute($data->reference_document_type_id) : null;
 


    $dispatchNote = new DispatchNote(
      id: 0,
      company: $company,
      branch: $branch,
      serie: $data->serie,
      correlativo: $data->correlativo,
      emission_reason: $emissionReason,
      description: $data->description,
      destination_branch: $destination,
      transport: $transportCompany,
      observations: $data->observations,
      num_orden_compra: $data->num_orden_compra,
      doc_referencia: $data->doc_referencia,
      num_referencia: $data->num_referencia,
      date_referencia: $data->date_referencia,
      status: $data->status,
      conductor: $driver,
      license_plate: $data->license_plate,
      total_weight: $data->total_weight,
      transfer_type: $data->transfer_type,
      vehicle_type: $data->vehicle_type,
      destination_branch_client:  $data->destination_branch_client,
      customer_id: $data->customer_id,
      supplier: $supplier,
      address_supplier: $supplierAddress,
      reference_document_type: $referenceDocumentType,
      created_at: '',
      estado_sunat: null
    );
    return $this->dispatchNoteRepository->save($dispatchNote);
  }
}

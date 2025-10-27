<?php

namespace App\Modules\DispatchNotes\application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DispatchNotes\application\DTOS\DispatchNoteDTO;
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

class UpdateDispatchNoteUseCase
{
   public function __construct(
   private readonly DispatchNotesRepositoryInterface $dispatchNoteRepository,
   private readonly CompanyRepositoryInterface $companyRepositoryInterface,
   private readonly BranchRepositoryInterface $branchRepository,
   private readonly SerieRepositoryInterface $serieRepositoryInterface,
   private readonly EmissionReasonRepositoryInterface $emissionReasonRepositoryInterface,
   private readonly TransportCompanyRepositoryInterface  $transportCompany,
    private readonly DocumentTypeRepositoryInterface  $documentTypeRepositoryInterface,
       private readonly DriverRepositoryInterface  $driverRepositoryInterface,
   ){}

    public function execute(DispatchNoteDTO $data, $id): DispatchNote
    {
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepositoryInterface);
        $company = $companyUseCase->execute($data->cia_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($data->branch_id);

         $emissionReasonUseCase = new FindByIdEmissionReasonUseCase($this->emissionReasonRepositoryInterface);
        $emissionReason = $emissionReasonUseCase->execute($data->emission_reason_id);

         $destinationUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $destination = $destinationUseCase->execute($data->destination_branch_id);

        $driverUseCase = new FindByIdDriverUseCase($this->driverRepositoryInterface);
        $driver = $driverUseCase->execute($data->cod_conductor);


         $transportCompanyUseCase = new FindByIdTransportCompanyUseCase($this->transportCompany);
        $transportCompany = $transportCompanyUseCase->execute($data->transport_id);

        $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentTypeRepositoryInterface);
        $documentType = $documentTypeUseCase->execute($data->document_type_id);

        //  $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        // $branch = $branchUseCase->execute($data->destination_branch_client);
        
        $dispatchNote = new DispatchNote(
          id:$id,
          company:$company,
          branch:$branch,
          serie:$data->serie,
          correlativo:$data->correlativo,
          date:$data->date,
          emission_reason:$emissionReason,
          description:$data->description,
          destination_branch:$destination,
          destination_address_customer:$data->destination_address_customer,
          transport:$transportCompany,
          observations:$data->observations,
          num_orden_compra:$data->num_orden_compra,
          doc_referencia:$data->doc_referencia,
          num_referencia:$data->num_referencia,
          date_referencia:$data->date_referencia,
          status:$data->status,
          conductor:$driver,
          license_plate:$data->license_plate,
          total_weight:$data->total_weight,
          transfer_type:$data->transfer_type,
          vehicle_type:$data->vehicle_type,
          document_type:$documentType,
          destination_branch_client:$data->destination_branch_client_id,
          customer_id:$data->customer_id   
            
        );
       return $this->dispatchNoteRepository->update($dispatchNote);
    }
}
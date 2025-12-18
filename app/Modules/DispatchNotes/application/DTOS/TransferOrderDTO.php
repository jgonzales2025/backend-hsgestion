<?php

namespace App\Modules\DispatchNotes\Application\DTOS;

class TransferOrderDTO
{
  public int $company_id;
  public int $branch_id;
  public string $serie;
  public int $emission_reason_id;
  public ?int $destination_branch_id;
  public ?string $observations;

  public function __construct(array $data)
  {
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->serie = $data['serie'];
        $this->emission_reason_id = $data['emission_reason_id'];
        $this->destination_branch_id = $data['destination_branch_id'];
        $this->observations = $data['observations'];
  }
}
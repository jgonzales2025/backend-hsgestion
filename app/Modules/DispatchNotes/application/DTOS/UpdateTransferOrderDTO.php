<?php

namespace App\Modules\DispatchNotes\Application\DTOs;

class UpdateTransferOrderDTO
{
  public int $branch_id;
  public int $emission_reason_id;
  public ?int $destination_branch_id;
  public ?string $observations;

  public function __construct(array $data)
  {
    $this->branch_id = $data['branch_id'];
    $this->emission_reason_id = $data['emission_reason_id'];
    $this->destination_branch_id = $data['destination_branch_id'];
    $this->observations = $data['observations'];
  }
}
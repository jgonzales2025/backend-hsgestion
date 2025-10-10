<?php
namespace App\Modules\Branch\Application\DTOs;

class BranchDTO {
    public $cia_id;
    public $name;
    public $address;
    public $email;
    public $start_date;
    public $serie;
    public $status;

    public function __construct(array $data)
    {
        $this->cia_id = $data['cia_id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->address = $data['address'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->start_date = $data['start_date'] ?? null;
        $this->serie = $data['serie'] ?? null;
        $this->status = $data['status'] ?? null;
    }
}

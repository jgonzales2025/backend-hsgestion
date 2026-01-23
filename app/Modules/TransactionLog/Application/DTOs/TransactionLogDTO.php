<?php

namespace App\Modules\TransactionLog\Application\DTOs;

class TransactionLogDTO
{
    public $user_id;
    public $role_name;
    public $description_log;
    public ?string $observations;
    public $action;
    public $company_id;
    public ?int $branch_id;
    public $document_type_id;
    public $serie;
    public $correlative;
    public $ip_address;
    public $user_agent;

    public function __construct(array $data)
    {
        $this->user_id = $data['user_id'];
        $this->role_name = $data['role_name'];
        $this->description_log = $data['description_log'];
        $this->observations = $data['observations'] ?? null;
        $this->action = $data['action'];
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'] ?? null;
        $this->document_type_id = $data['document_type_id'];
        $this->serie = $data['serie'];
        $this->correlative = $data['correlative'];
        $this->ip_address = $data['ip_address'];
        $this->user_agent = $data['user_agent'];
    }
}

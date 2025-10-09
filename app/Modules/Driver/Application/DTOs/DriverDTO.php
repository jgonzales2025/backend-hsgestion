<?php

namespace App\Modules\Driver\Application\DTOs;

class DriverDTO
{
    public $customer_document_type_id;
    public $doc_number;
    public $name;
    public $pat_surname;
    public $mat_surname;
    public $status;
    public $license;

    public function __construct(array $data)
    {
        $this->customer_document_type_id = $data['customer_document_type_id'];
        $this->doc_number = $data['doc_number'];
        $this->name = $data['name'];
        $this->pat_surname = $data['pat_surname'];
        $this->mat_surname = $data['mat_surname'];
        $this->status = $data['status'];
        $this->license = $data['license'];
    }
}

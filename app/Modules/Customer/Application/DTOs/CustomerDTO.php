<?php

namespace App\Modules\Customer\Application\DTOs;

class CustomerDTO
{
    public $record_type_id;
    public $customer_document_type_id;
    public $document_number;
    public $company_name;
    public $name;
    public $lastname;
    public $second_lastname;
    public $customer_type_id;
    public $customer_type_name;
    public $fax;
    public $contact;
    public $is_withholding_applicable;
    public $status;

    public function __construct(array $data)
    {
        $this->record_type_id = $data['record_type_id'];
        $this->customer_document_type_id = $data['customer_document_type_id'];
        $this->document_number = $data['document_number'];
        $this->company_name = $data['company_name'];
        $this->name = $data['name'];
        $this->lastname = $data['lastname'];
        $this->second_lastname = $data['second_lastname'];
        $this->customer_type_id = $data['customer_type_id'];
        $this->customer_type_name = $data['customer_type_name'];
        $this->fax = $data['fax'];
        $this->contact = $data['contact'];
        $this->is_withholding_applicable = $data['is_withholding_applicable'];
        $this->status = $data['status'];
    }
}

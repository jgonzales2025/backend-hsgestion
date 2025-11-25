<?php

namespace App\Modules\PettyCashReceipt\Application\DTOS;

class PettyCashReceiptDTO
{
    public int $company_id;
    public int  $document_type_id;
    public string $series;
    public ?string $correlative;
    public string $date;
    public string $delivered_to;
    public int $reason_code_id;
    public int $currency_type;
    public float $amount;
    public string $observation;
    public int $status;
    public int $branch_id;

    public function __construct(array $array)
    {
        $this->company_id = $array['company_id'] ?? 1;
        $this->document_type_id = $array['document_type'];
        $this->series = $array['series'];
        $this->correlative = $array['correlative'] ?? '';
        $this->date = $array['date'];
        $this->delivered_to = $array['delivered_to'];
        $this->reason_code_id = $array['reason_code'];
        $this->currency_type = $array['currency_type'];
        $this->amount = $array['amount'];
        $this->observation = $array['observation'] ?? '';
        $this->status = $array['status'] ?? true;
        $this->branch_id = $array['branch_id'];
    }
}

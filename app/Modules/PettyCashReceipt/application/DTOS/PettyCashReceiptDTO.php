<?php

namespace App\Modules\PettyCashReceipt\Application\DTOS;

class PettyCashReceiptDTO{
    public int $company;
    public int $document_type;
    public string $series;
    public string $correlative;
    public string $date;
    public string $delivered_to;
    public int $reason_code;
    public int $currency_type;
    public float $amount;
    public string $observation;
    public int $status;
    public int $created_by;
    public string $created_at_manual;
    public int $updated_by;
    public string $updated_at_manual;

    public function __construct(array $array){
        $this->company = $array['company'] ?? 1;
        $this->document_type = $array['document_type'];
        $this->series = $array['series'];
        $this->correlative = $array['correlative'];
        $this->date = $array['date'];
        $this->delivered_to = $array['delivered_to'];
        $this->reason_code = $array['reason_code'];
        $this->currency_type = $array['currency_type'];
        $this->amount = $array['amount'];
        $this->observation = $array['observation'];
        $this->status = $array['status'];
        $this->created_by = $array['created_by'];
        $this->created_at_manual = $array['created_at_manual'];
        $this->updated_by = $array['updated_by'];
        $this->updated_at_manual = $array['updated_at_manual'];
    }
}

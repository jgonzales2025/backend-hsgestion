<?php

namespace App\Modules\Sale\Application\DTOs;

class SaleDTO
{
    public $company_id;
    public $branch_id;
    public $document_type_id;
    public ?string $serie;
    public ?string $document_number;
    public $parallel_rate;
    public $customer_id;
    public $date;
    public $due_date;
    public $days;
    public $user_id;
    public ?int $user_sale_id;
    public $payment_type_id;
    public ?string $observations;
    public $currency_type_id;
    public $subtotal;
    public $igv;
    public $total;
    public ?float $saldo;
    public ?float $amount_amortized;
    public ?int $payment_status;
    public ?bool $is_locked;
    public ?int $id_prof;
    public ?string $serie_prof;
    public ?string $correlative_prof;
    public ?string $purchase_order;
    public ?int $user_authorized_id;
    public ?int $coddetrac;
    public ?float $pordetrac;
    public ?float $impdetracs;
    public ?float $impdetracd;
    public ?float $stretencion;
    public ?float $porretencion;
    public ?float $impretens;
    public ?float $impretend;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->document_type_id = $data['document_type_id'];
        $this->serie = $data['serie'] ?? null;
        $this->document_number = $data['document_number'] ?? null;
        $this->parallel_rate = $data['parallel_rate'];
        $this->customer_id = $data['customer_id'];
        $this->date = $data['date'];
        $this->due_date = $data['due_date'];
        $this->days = $data['days'];
        $this->user_id = $data['user_id'];
        $this->user_sale_id = $data['user_sale_id'] ?? null;
        $this->payment_type_id = $data['payment_type_id'];
        $this->observations = $data['observations'] ?? null;
        $this->currency_type_id = $data['currency_type_id'];
        $this->subtotal = $data['subtotal'];
        $this->igv = $data['igv'];
        $this->total = $data['total'];
        $this->saldo = $data['saldo'] ?? null;
        $this->amount_amortized = $data['amount_amortized'] ?? null;
        $this->payment_status = $data['payment_status'] ?? null;
        $this->is_locked = $data['is_locked'] ?? null;
        $this->id_prof = $data['id_prof'] ?? null;
        $this->serie_prof = $data['serie_prof'] ?? null;
        $this->correlative_prof = $data['correlative_prof'] ?? null;
        $this->purchase_order = $data['purchase_order'] ?? null;
        $this->user_authorized_id = $data['user_authorized_id'] ?? null;
        $this->coddetrac = $data['coddetrac'] ?? null;
        $this->pordetrac = $data['pordetrac'] ?? null;
        $this->impdetracs = ($data['currency_type_id'] === 1 ? round($data['total'] * ($data['pordetrac'] / 100), 2) : round($data['total'] * ($data['pordetrac'] / 100) * $data['parallel_rate'], 2)) ?? null;
        $this->impdetracd = ($data['currency_type_id'] === 2 ? round($data['total'] * ($data['pordetrac'] / 100), 2) : round($data['total'] * ($data['pordetrac'] / 100) / $data['parallel_rate'], 2)) ?? null;
        $this->stretencion = $data['stretencion'] ?? null;
        $this->porretencion = $data['porretencion'] ?? null;
        $this->impretens = ($data['currency_type_id'] === 1 ? round($data['total'] * ($data['porretencion'] / 100), 2) : round(($data['total'] * ($data['porretencion'] / 100)) * $data['parallel_rate'], 2)) ?? null;
        $this->impretend = ($data['currency_type_id'] === 2 ? round($data['total'] * ($data['porretencion'] / 100), 2) : round($data['total'] * ($data['porretencion'] / 100) / $data['parallel_rate'], 2)) ?? null;
    }
}

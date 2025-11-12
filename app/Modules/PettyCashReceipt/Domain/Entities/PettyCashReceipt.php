<?php

namespace App\Modules\PettyCashReceipt\Domain\Entities;

use App\Modules\Company\Domain\Entities\Company;

class PettyCashReceipt
{
    private ?int $id;
    private ?Company $company;
    private string $tip_doc;
    private string $serie;
    private string $correlativo;
    private string $fecha;
    private ?string $delivered;
    private int $reasonCode;
    private int $currencyType;
    private float $amount;
    private string $note;
    private int $status;
    private ?int $adi_user;
    private ?string $add_date;
    private ?int $mod_user;
    private ?string $modifyDate;

    public function __construct(
        ?int $id,
        ?Company $company,
        string $tip_doc,
        string $serie,
        string $correlativo,
        string $fecha,
        ?string $delivered,
        int $reasonCode,
        int $currencyType,
        float $amount,
        string $note,
        int $status,
        ?int $adi_user,
        ?string $add_date,
        ?int $mod_user,
        ?string $modifyDate
    ) {
        $this->id = $id;
        $this->company = $company;
        $this->tip_doc = $tip_doc;
        $this->serie = $serie;
        $this->correlativo = $correlativo;
        $this->fecha = $fecha;
        $this->delivered = $delivered;
        $this->reasonCode = $reasonCode;
        $this->currencyType = $currencyType;
        $this->amount = $amount;
        $this->note = $note;
        $this->status = $status;
        $this->adi_user = $adi_user;
        $this->add_date = $add_date;
        $this->mod_user = $mod_user;
        $this->modifyDate = $modifyDate;
    }
   public function getId() {
        return $this->id;
    }
    public function getCompany() {
        return $this->company;
    }
    public function getTipDoc() {
        return $this->tip_doc;
    }
    public function getSerie() {
        return $this->serie;
    }
    public function getCorrelativo() {
        return $this->correlativo;
    }
    public function getFecha() {
        return $this->fecha;
    }
    public function getDelivered() {
        return $this->delivered;
    }
    public function getReasonCode() {
        return $this->reasonCode;
    }
    public function getCurrencyType() {
        return $this->currencyType;
    }
    public function getAmount() {
        return $this->amount;
    }
    public function getNote() {
        return $this->note;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getAdiUser() {
        return $this->adi_user;
    }
    public function getAddDate() {
        return $this->add_date;
    }
    public function getModUser() {
        return $this->mod_user;
    }
    public function getModifyDate() {
        return $this->modifyDate;
    }

}
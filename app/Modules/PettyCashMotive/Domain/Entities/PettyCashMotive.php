<?php

namespace App\Modules\PettyCashMotive\Domain\Entities;

class PettyCashMotive
{
    private ?int $id;
    private int $company_id;
    private string $description;
    private int $receipt_type;
    private int $user_id;
    private string $date;
    private int $user_mod;
    private string $date_mod;
    private bool $status;

    public function __construct(
        ?int $id,
        int $company_id,
        string $description,
        int $receipt_type,
        int $user_id,
        string $date,
        int $user_mod,
        string $date_mod,
        bool $status
    ) {
       $this->id = $id;
       $this->company_id = $company_id;
       $this->description = $description;
       $this->receipt_type = $receipt_type;
       $this->user_id = $user_id;
       $this->date = $date;
       $this->user_mod = $user_mod;
       $this->date_mod = $date_mod;
       $this->status = $status;
    }
    public function getId():int|null {
        return $this->id;
    }
    public function getCompanyId() {
        return $this->company_id;
    }
    public function getDescription() {
        return $this->description;
    }
    public function getReceiptType() {
        return $this->receipt_type;
    }
    public function getUserId() {
        return $this->user_id;
    }
    public function getDate() {
        return $this->date;
    }
    public function getUserMod() {
        return $this->user_mod;
    }
    public function getDateMod() {
        return $this->date_mod;
    }
    public function getStatus() {
        return $this->status;
    }


}


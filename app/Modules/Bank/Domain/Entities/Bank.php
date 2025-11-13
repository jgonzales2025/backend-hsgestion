<?php

namespace App\Modules\Bank\Domain\Entities;

use App\Modules\Company\Domain\Entities\Company;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\User\Domain\Entities\User;

class Bank
{
    private ?int $id;
    private string $name;
    private string $account_number;
    private ?CurrencyType $currency_type;
    private ?User $user;
    private ?string $date_at;
    private ?Company $company;
    private ?int $status;

    public function __construct(?int $id, string $name, string $account_number, ?CurrencyType $currency_type, ?User $user, ?string $date_at, ?Company $company, ?int $status = 1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->account_number = $account_number;
        $this->currency_type = $currency_type;
        $this->user = $user;
        $this->date_at = $date_at;
        $this->company = $company;
        $this->status = $status;
    }

    public function getId(): int|null { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getAccountNumber(): string { return $this->account_number; }
    public function getCurrencyType(): CurrencyType|null { return $this->currency_type; }
    public function getUser(): User|null { return $this->user; }
    public function getDateAt(): string|null { return $this->date_at; }
    public function getCompany(): Company|null { return $this->company; }
    public function getStatus(): ?int { return $this->status; }
}

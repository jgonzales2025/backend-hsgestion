<?php

namespace App\Modules\DigitalWallet\Domain\Entities;

use App\Modules\Company\Domain\Entities\Company;
use App\Modules\User\Domain\Entities\User;

class DigitalWallet
{
    private ?int $id;
    private string $name;
    private string $phone;
    private Company $company;
    private User $user;
    private int $status;

    public function __construct(?int $id, string $name, string $phone, Company $company, User $user, int $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->company = $company;
        $this->user = $user;
        $this->status = $status;
    }

    public function getId(): int|null { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPhone(): string { return $this->phone; }
    public function getCompany(): Company { return $this->company; }
    public function getUser(): User { return $this->user; }
    public function getStatus(): int { return $this->status; }
}

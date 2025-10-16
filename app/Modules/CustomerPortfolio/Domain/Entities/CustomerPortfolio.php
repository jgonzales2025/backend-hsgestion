<?php

namespace App\Modules\CustomerPortfolio\Domain\Entities;

use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\User\Domain\Entities\User;

class CustomerPortfolio
{
    private int $id;
    private Customer $customer;
    private User $user;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(int $id, Customer $customer, User $user, ?string $created_at, ?string $updated_at)
    {
        $this->id = $id;
        $this->customer = $customer;
        $this->user = $user;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int { return $this->id; }
    public function getCustomer(): Customer { return $this->customer; }
    public function getUser(): User { return $this->user; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getUpdatedAt(): ?string { return $this->updated_at; }
}

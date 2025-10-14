<?php

namespace App\Modules\CustomerType\Domain\Interfaces;

interface CustomerTypeRepositoryInterface
{
    public function findAll(): array;
}

<?php

namespace App\Modules\Detraction\Domain\Interface;

interface DetractionRepositoryInterface
{
    public function findAll(): array;
}
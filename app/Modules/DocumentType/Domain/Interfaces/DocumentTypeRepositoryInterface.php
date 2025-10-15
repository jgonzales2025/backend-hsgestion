<?php

namespace App\Modules\DocumentType\Domain\Interfaces;

interface DocumentTypeRepositoryInterface
{
    public function findAll(): array;
}

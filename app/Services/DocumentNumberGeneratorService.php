<?php

namespace App\Services;

class DocumentNumberGeneratorService
{
    public function generateNextNumber(?string $lastDocumentNumber): string
    {
        if ($lastDocumentNumber === null) {
            return '00000001';
        }

        $nextNumber = intval($lastDocumentNumber) + 1;
        return str_pad($nextNumber, 8, '0', STR_PAD_LEFT);
    }
}

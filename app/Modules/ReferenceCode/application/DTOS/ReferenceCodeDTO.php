<?php

namespace App\Modules\ReferenceCode\Application\DTOs;

class ReferenceCodeDTO
{
    public int $id;
    public string $refCode;
    public int $articleId;
    public string $dateAt;
    public int $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0; // por si es nuevo y aún no tiene ID
        $this->refCode = $data['refCode'];
        $this->articleId = $data['articleId'];
        $this->dateAt = $data['dateAt'];
        $this->status = $data['status'];
    }
}

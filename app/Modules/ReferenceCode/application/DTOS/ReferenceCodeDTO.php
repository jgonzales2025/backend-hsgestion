<?php

namespace App\Modules\ReferenceCode\Application\DTOs;

class ReferenceCodeDTO
{
    public int $id;
    public string $ref_code;
    public int $article_Id;
    public string $dateAt;
    public int $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0; // por si es nuevo y aún no tiene ID
        $this->ref_code = $data['ref_code'];
        $this->article_Id = $data['article_id'];
        $this->status = $data['status'];
    }
}

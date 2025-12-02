<?php

namespace App\Modules\ReferenceCode\Application\DTOs;

class ReferenceCodeDTO
{
    public int $id;
    public string $ref_code;
    public int $article_id;
    public int $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->ref_code = $data['ref_code'] ;
        $this->article_id = $data['article_id'] ?? 0;
        $this->status = $data['status'] ?? 1;
    }
}

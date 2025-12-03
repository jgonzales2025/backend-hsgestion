<?php

namespace App\Modules\DetailPcCompatible\application\DTOS;

class DetailPcCompatibleDTO
{
    public int $article_major_id;
    public int $article_accesory_id;
    public bool $status;

    public function __construct(array $data)
    {
        $this->article_major_id = $data['article_major_id'];
        $this->article_accesory_id = $data['article_accesory_id'];
        $this->status = $data['status'] ?? true;
    }
}

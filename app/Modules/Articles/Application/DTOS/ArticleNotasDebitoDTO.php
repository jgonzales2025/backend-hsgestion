<?php

namespace App\Modules\Articles\Application\DTOS;

class ArticleNotasDebitoDTO
{
    public int $user_id;
    public int $company_id;
    public string $filt_NameEsp;
    public ?bool $status_Esp;

    public function __construct($array)
    {
        $this->company_id = $array['company_id'] ;
        $this->user_id = $array['user_id'];
        $this->filt_NameEsp = $array['description'] ?? '';
        $this->status_Esp = $array['status_Esp'] ?? true;
    }

}
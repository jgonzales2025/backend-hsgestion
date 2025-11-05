<?php

namespace App\Modules\Articles\Domain\Entities;

class ArticleNotasDebito
{
    private ?int $id;
    private int $user_id;
    private int $company_id;
    private ?string $filt_NameEsp;
    private ?bool $status_Esp = true;

    public function __construct(
        ?int $id,
        int $user_id,
        int $company_id,
        ?string $filt_NameEsp,
        ?bool $status_Esp
    )
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->company_id = $company_id;
        $this->filt_NameEsp = $filt_NameEsp;
        $this->status_Esp = $status_Esp;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
        public function getUserId(): int
    {
        return $this->user_id;
    }
    public function getCompanyId(): int
    {
        return $this->company_id;
    }
    public function getFiltNameEsp(): ?string
    {
        return $this->filt_NameEsp;
    }
    public function getStatusEsp(): ?bool
    {
        return $this->status_Esp;
    }
}
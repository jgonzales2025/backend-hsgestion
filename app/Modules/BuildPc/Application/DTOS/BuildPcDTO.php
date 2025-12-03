<?php

namespace App\Modules\BuildPc\application\DTOS;

class BuildPcDTO
{
    public string $name;
    public string $description;
    public float $total_price;
    public int $user_id;
    public bool $status;
    public array $details;
    public int $quantity;
    public int $article_ensamb_id;

    public function __construct(array $data)
    {

        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->total_price = $data['total_price'];
        $this->user_id = $data['user_id'];
        $this->status = $data['status'] ?? 1;
        $this->details = $data['details'] ?? [];
        $this->quantity = $data['quantity'] ?? 0;
        $this->article_ensamb_id = $data['article_ensamb_id'] ?? null;
    }
}

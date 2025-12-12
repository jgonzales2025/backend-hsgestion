<?php

namespace App\Modules\BuildPc\application\DTOS;

class BuildPcDTO
{
    public int $company_id;
    public string $name;
    public string $description;
    public float $total_price;
    public int $user_id;
    public bool $status;
    public array $details;
    public float $min;
    public float $max;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->user_id = $data['user_id'];
        $this->status = $data['status'] ?? 1;
        $this->details = $data['details'] ?? [];
        $this->min = $data['min'] ?? 0;
        $this->max = $data['max'] ?? 0;
    }
}

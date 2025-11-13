<?php

namespace App\Modules\Brand\Application\DTOs;

class BrandDTO
{
    public $id;
    public $name;
    public $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
    }
}

<?php

namespace App\Modules\Category\Application\DTOs;

class CategoryDTO
{
    public $name;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
    }
}

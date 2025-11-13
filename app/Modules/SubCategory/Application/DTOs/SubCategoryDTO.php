<?php

namespace App\Modules\SubCategory\Application\DTOs;

class SubCategoryDTO
{
    public $name;
    public $category_id;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->category_id = $data['category_id'];
    }
}

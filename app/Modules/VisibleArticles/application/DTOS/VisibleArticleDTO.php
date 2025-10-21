<?php

namespace App\Modules\VisibleArticles\Application\DTOS;

class VisibleArticleDTO
{

    public $status;

    public function __construct(array $data)
    {

        $this->status = $data['status'];


    }
}
<?php

namespace App\Modules\DispatchArticle\Domain\Interface;

use App\Modules\DispatchArticle\Domain\Entities\DispatchArticle;

interface DispatchArticleRepositoryInterface{
   
    public function findAll():array;
    public function save(DispatchArticle $dispatchArticle):?DispatchArticle;
}
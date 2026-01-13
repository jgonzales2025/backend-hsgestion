<?php

namespace App\Modules\ArticleType\Domain\Interface;

interface ArticleTypeRepositoryInterface
{
  public function findAll();
  public function findById(int $id);   
}
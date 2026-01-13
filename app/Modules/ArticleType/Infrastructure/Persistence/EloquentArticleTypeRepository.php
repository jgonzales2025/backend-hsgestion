<?php

namespace App\Modules\ArticleType\Infrastructure\Persistence;

use App\Modules\ArticleType\Domain\Interface\ArticleTypeRepositoryInterface;
use App\Modules\ArticleType\Infrastructure\Models\EloquentArticleType;

class EloquentArticleTypeRepository implements ArticleTypeRepositoryInterface
{
    public function findAll()
    {
        $eloquentArticleType = EloquentArticleType::all();
        return $eloquentArticleType;
    }
    public function findById(int $id)
    {
        $eloquentArticleType = EloquentArticleType::find($id);
        return $eloquentArticleType;
    }
     
}
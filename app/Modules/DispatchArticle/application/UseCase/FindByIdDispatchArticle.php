<?php

namespace App\Modules\DispatchArticle\Application\UseCase;

use App\Modules\DispatchArticle\Domain\Entities\DispatchArticle;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;

class FindByIdDispatchArticle{

    public function __construct(private readonly DispatchArticleRepositoryInterface $dispatchArticle){

    }

    public function execute(int $id){
        return $this->dispatchArticle->findById($id);
    }
}
<?php

namespace App\Modules\DispatchArticle\Application\UseCase;

use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;

class FindAllDispatchArticleUseCase{
    public function __construct(private readonly DispatchArticleRepositoryInterface $dispatchArticleRepositoryInterface){

    }
    public function execute(){
        return $this->dispatchArticleRepositoryInterface->findAll();
    }
}
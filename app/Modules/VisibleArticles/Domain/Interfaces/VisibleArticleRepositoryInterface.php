<?php

namespace App\Modules\VisibleArticles\Domain\Interfaces;

use App\Modules\VisibleArticles\Domain\Entities\VisibleArticle;

interface VisibleArticleRepositoryInterface{
     public function findById(int $id):?VisibleArticle;
     public function update(VisibleArticle $visibleArticle):void;
        public function mostrar(int $id):array;
}
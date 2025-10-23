<?php

namespace App\Modules\SaleArticle\Domain\Interfaces;

use App\Modules\SaleArticle\Domain\Entities\SaleArticle;

interface SaleArticleRepositoryInterface
{
    public function save(SaleArticle $saleArticle): ?SaleArticle;
}

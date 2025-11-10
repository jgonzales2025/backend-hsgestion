<?php

namespace App\Modules\SaleItemSerial\Application\DTOs;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\SaleArticle\Domain\Entities\SaleArticle;

class SaleItemSerialDTO
{
    public Sale $sale;
    public SaleArticle $article;
    public string $serial;

    public function __construct(array $data)
    {
        $this->sale = $data['sale'];
        $this->article = $data['article'];
        $this->serial = $data['serial'];
    }
}

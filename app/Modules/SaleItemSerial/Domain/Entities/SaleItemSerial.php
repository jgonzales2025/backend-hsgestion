<?php

namespace App\Modules\SaleItemSerial\Domain\Entities;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\SaleArticle\Domain\Entities\SaleArticle;

class SaleItemSerial
{
    private int $id;
    private Sale $sale;
    private SaleArticle $article;
    private string $serial;

    public function __construct(int $id, Sale $sale, SaleArticle $article, string $serial)
    {
        $this->id = $id;
        $this->sale = $sale;
        $this->article = $article;
        $this->serial = $serial;
    }

    public function getId(): int { return $this->id; }
    public function getSale(): Sale { return $this->sale;}
    public function getArticle(): SaleArticle { return $this->article; }
    public function getSerial(): string { return $this->serial; }
}

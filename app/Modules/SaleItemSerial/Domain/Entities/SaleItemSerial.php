<?php

namespace App\Modules\SaleItemSerial\Domain\Entities;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Sale\Domain\Entities\Sale;

class SaleItemSerial
{
    private Sale $sale;
    private Article $article;
    private string $serial;
    private int $status;

    public function __construct(Sale $sale, Article $article, string $serial, int $status = 0)
    {
        $this->sale = $sale;
        $this->article = $article;
        $this->serial = $serial;
        $this->status = $status;
    }

    public function getSale(): Sale { return $this->sale;}
    public function getArticle(): Article { return $this->article; }
    public function getSerial(): string { return $this->serial; }
    public function getStatus(): int { return $this->status; }
}

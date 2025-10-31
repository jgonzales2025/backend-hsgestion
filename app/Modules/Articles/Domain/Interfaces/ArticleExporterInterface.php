<?php

namespace App\Modules\Articles\Domain\Interfaces;

use App\Modules\Articles\Domain\Entities\Article;
use Illuminate\Support\Collection;

interface ArticleExporterInterface
{
    public function export(Article $articles): string;
}
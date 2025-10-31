<?php
namespace App\Modules\Articles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleExporterInterface;
use Maatwebsite\Excel\Facades\Excel;


class ExcelArticleExporter implements ArticleExporterInterface
{
    public function export(Article $articles): string
    {
        $fileName = 'articulos_' . now()->format('Y-m-d_His') . '.xlsx';
        $filePath = 'exports/' . $fileName;
        
        Excel::store(
            new ArticlesExport($articles), 
            $filePath, 
            'public'
        );
        
        return $filePath;
    }
}
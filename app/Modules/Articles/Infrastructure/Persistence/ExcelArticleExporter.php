<?php
namespace App\Modules\Articles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleExporterInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExcelArticleExporter implements ArticleExporterInterface
{
    public function export(Collection $article): string
    {
        $fileName = 'articulo_' . now()->format('Y-m-d_His') . '.xlsx';
        $filePath = 'exports/' . $fileName;

        // Convertir el artículo único en una colección
        // $collection = collect([$article]);

        Excel::store(
            new ArticlesExport($article), 
            $filePath, 
            'public'
        );

        return $filePath;
    }
}

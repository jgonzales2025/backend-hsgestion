<?php

namespace App\Modules\Articles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArticlesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private Collection $articles) {}

    public function collection(): Collection
    {
        // Convertir el artículo único en una colección
         return $this->articles;
    }

        public function headings(): array
    {
        return [
            'ID',
            'Código Fabricante',
            'Descripción',
            'Precio Compra',
            'Precio Público',
            'Stock Mínimo',
            'Categoría',
            'Marca',
            'Moneda',
            'Unidad Medida',
        ];
    }

  public function map($article): array
{
    return [
        $article->getId(),
        $article->getCodFab(),
        $article->getDescription(),
        number_format($article->getPurchasePrice(), 2),
        $article->getPublicPrice(),
        $article->getMinStock(),
        $article->getCategory()?->getName() ?? '',
        $article->getBrand()?->getName() ?? '',
        $article->getCurrencyType()?->getName() ?? '',
        $article->getMeasurementUnit()?->getName() ?? '',
    ];
}


    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

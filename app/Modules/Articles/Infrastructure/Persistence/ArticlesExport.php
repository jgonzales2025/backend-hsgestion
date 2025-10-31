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
    public function __construct(private Article $article) {}

    public function collection(): Collection
    {
        // Convertir el artículo único en una colección
        return collect([$this->article]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Precio',
            'Stock',
            'Descripción',
            'Fecha Creación'
        ];
    }

    public function map($article): array
    {
        return [
            $article->id,
            $article->nombre,
            number_format($article->precio, 2),
            $article->stock,
            $article->descripcion ?? '',
            $article->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

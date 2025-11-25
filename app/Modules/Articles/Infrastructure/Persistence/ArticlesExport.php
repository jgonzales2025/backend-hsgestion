<?php

namespace App\Modules\Articles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArticlesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
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
            number_format($article->getPublicPrice(), 2),
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Congelar la fila de cabecera
                $sheet->freezePane('A2');

                // Aplicar autofiltro desde la cabecera hasta la última fila
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");

                // Estilo de cabecera: fondo y alineación
                $headerRange = "A1:{$highestColumn}1";
                $sheet->getStyle($headerRange)
                      ->getFill()
                      ->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()
                      ->setARGB('FFE6F0FF'); // azul claro

                $sheet->getStyle($headerRange)
                      ->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}

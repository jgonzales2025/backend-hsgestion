<?php

namespace App\Modules\Statistics\Infrastructure\Persistence;

use App\Modules\Statistics\Domain\Interfaces\StatisticsRepositoryInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExcelListaPrecio implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private readonly StatisticsRepositoryInterface $statisticsRepository,
        private readonly int $p_codma,
        private readonly int $p_codcategoria,
        private readonly int $p_status,
        private readonly int $p_moneda,
        private readonly int $p_orden,
        private readonly string $title
    ) {
    }

    public function collection()
    {
        return $this->statisticsRepository->getListaPrecio(
            p_codma: $this->p_codma,
            p_codcategoria: $this->p_codcategoria,
            p_status: $this->p_status,
            p_moneda: $this->p_moneda,
            p_orden: $this->p_orden
        );
    }

    public function headings(): array
    {
        return [
            'CODIGO',
            'DESCRIPCION',
            'PRECIO',
            'ESTADO',
            'CATEGORIA',
            'MARCA'
        ];
    }

    public function map($row): array
    {
        return [
            $row['CODIGO'],
            $row['DESCRIPCION'],
            $row['PRECIO'],
            $row['ESTADO'],
            $row['CATEGORIA'],
            $row['MARCA']
        ];
    }

    public function title(): string
    {
        return 'Lista de Precios';
    }
   public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->insertNewRowBefore(1, 1);
                $sheet->mergeCells("A1:{$highestColumn}1");
                $sheet->setCellValue('A1', mb_strtoupper($this->title));

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]); 

                $highestRow = $sheet->getHighestRow();

                $sheet->freezePane('A3');

                $sheet->setAutoFilter("A2:{$highestColumn}{$highestRow}");

                $headerRange = "A2:{$highestColumn}2";
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                ]);

                $sheet->getStyle("H3:M{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $sheet->getStyle("A3:G{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("H3:M{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }

}
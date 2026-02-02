<?php

namespace App\Modules\Statistics\Infrastructure\Persistence;

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
use Illuminate\Support\Collection;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(
        private array $data,
        private string $companyName,
        private string $startDate,
        private string $endDate
    ) {}

    public function collection(): Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        // Row 1: Company and Current DateTime
        // Row 2: Report Title
        // Row 3: Date Range
        // Row 4: Empty
        // Row 5: Table Headers
        return [
            [$this->companyName, '', '', '', '', now()->format('d/m/Y H:i')],
            ['INFORMES DE VENTAS S/INC IGV'],
            ['DESDE ' . ($this->startDate ?: '') . ' HASTA ' . ($this->endDate ?: '')],
            [''],
            ['CÓDIGO', 'DESCRIPCION', 'CANTIDAD', 'UDM', 'S/', 'US$']
        ];
    }

    public function map($row): array
    {
        return [
            data_get($row, 'CODIGO') ?? '',
            data_get($row, 'DESCRIPCION') ?? '',
            data_get($row, 'CANTIDAD') ?? 0,
            data_get($row, 'UDM') ?? '',
            number_format((float)(data_get($row, 'S/') ?? 0), 2, '.', ''),
            number_format((float)(data_get($row, 'US$') ?? 0), 2, '.', '')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            3 => ['font' => ['bold' => true, 'size' => 11]],
            5 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FF000000']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                // Merge Title and Dates
                $sheet->mergeCells("A2:{$highestColumn}2");
                $sheet->mergeCells("A3:{$highestColumn}3");
                $sheet->getStyle("A2:A3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Add borders to the data table (Row 5 to last)
                $tableRange = "A5:{$highestColumn}{$highestRow}";
                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Align numeric columns
                $sheet->getStyle("C5:C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E6:F{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }
}

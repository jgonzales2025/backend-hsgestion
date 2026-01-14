<?php

namespace App\Modules\Purchases\Infrastructure\Persistence;

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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class GenericExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{

    public function __construct(private Collection $purchases, private string $title) {}

    public function collection(): Collection
    {
        return $this->purchases;
    }

    public function headings(): array
    {
        return [
            'T/D',
            'SERIE',
            'CORRELATIVO',
            'FECHA',
            'RUC/DNI',
            'RAZON SOCIAL',
            'T/C',
            'VALOR S/.',
            'IGV S/.',
            'TOTAL S/.',
            'VALOR USD',
            'IGV USD',
            'TOTAL USD',
        ];
    }

    public function map($purchases): array 
    {
        return [
            $purchases->{'T/D'} ?? '',
            $purchases->{'SERIE'} ?? '',
            $purchases->{'CORRELATIVO'} ?? '',
            $purchases->{'FECHA'} ?? '',
            $purchases->{'RUC/DNI'} ?? '',
            $purchases->{'RAZÓN SOCIAL'} ?? '',
            (float) ($purchases->{'T/C'} ?? 0),
            (float) ($purchases->{'VALOR  S/'} ?? 0),
            (float) ($purchases->{'IGV S/'} ?? 0),
            (float) ($purchases->{'TOTAL  S/'} ?? 0),
            (float) ($purchases->{'VALOR  USD'} ?? 0),
            (float) ($purchases->{'IGV USD'} ?? 0),
            (float) ($purchases->{'TOTAL  USD'} ?? 0),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->insertNewRowBefore(1, 1);
                $sheet->mergeCells("A1:{$highestColumn}1");
                $sheet->setCellValue('A1', mb_strtoupper($this->title));

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'color' => ['rgb' => '000000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->freezePane('A3');
                $sheet->setAutoFilter("A2:{$highestColumn}{$highestRow}");

                $headerRange = "A2:{$highestColumn}2";
                $sheet->getStyle($headerRange)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFE6F0FF');

                $sheet->getStyle($headerRange)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("H3:M{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $sheet->getStyle("A3:M{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },

        ];
    }
}

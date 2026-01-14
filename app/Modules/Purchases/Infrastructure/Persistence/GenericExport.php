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


class GenericExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(
        private Collection $purchases,
        private string $title = 'Registro'
    ) {}

    public function collection(): Collection
    {
        return $this->purchases;
    }

    public function headings(): array
    {
        return [
            'ID',
            'SERIE',
            'CORRELATIVO',
            'FECHA',
            'RUC/DNI',
            'RAZON SOCIAL',
            'T/C',
            'VALOR S/.',
            'IGV S/.',
            'Total S/.',
            'VALOR USD',
            'IGV USD',
            'Total USD',
        ];
    }

    public function map($purchases): array
    {
        // Convertimos a array para manejar tanto objetos stdClass como arrays asociativos
        $data = is_object($purchases) ? get_object_vars($purchases) : (array)$purchases;

        // Limpiamos los valores de caracteres nulos y formateamos
        $cleanData = array_map(function ($value) {
            if ($value === null) return '';
            if (is_string($value)) {
                $value = str_replace("\0", '', $value);
                $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
            }
            return $value;
        }, $data);

        // Retornamos solo los valores en el orden que vienen del SP
        // Esto asegura que si el SP devuelve 13 columnas, se llenen las 13 cabeceras asignadas
        return array_values($cleanData);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // El encabezado ahora está en la fila 2 si hay título
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                // Insertar título en la primera fila
                $sheet->insertNewRowBefore(1, 1);
                $sheet->mergeCells("A1:{$highestColumn}1");
                $sheet->setCellValue('A1', mb_strtoupper($this->title));

                // Estilo del título
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Ajustar encabezados
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

                // Ajustar altura de la fila del título
                $sheet->getRowDimension(1)->setRowHeight(30);

                $sheet->getStyle("H3:H{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $sheet->getStyle("I3:I{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $sheet->getStyle("J3:J{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $sheet->getStyle("K3:K{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $sheet->getStyle("L3:L{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                $sheet->getStyle("M3:M{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            }

        ];
    }
}

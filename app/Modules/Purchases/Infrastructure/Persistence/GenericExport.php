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


class GenericExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithStyles, ShouldAutoSize
{

    public function __construct(
        private Collection $purchases,
        private string $title,
        private string $companyName = '',
        private ?string $dateStart = null,
        private ?string $dateEnd = null
    ) {}

    public function collection(): Collection
    {
        return $this->purchases;
    }

    public function headings(): array
    {
        $dateRange = 'FECHA EMISIÓN: ' . now()->format('d/m/Y');
        if ($this->dateStart && $this->dateEnd) {
            $dateRange .= '   RANGO: ' . date('d/m/Y', strtotime($this->dateStart)) . ' - ' . date('d/m/Y', strtotime($this->dateEnd));
        }

        return [
            [$this->companyName],
            [mb_strtoupper($this->title)],
            [$dateRange],
            [''], // Espacio
            [
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
            ]
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
            (float) ($purchases->{'VALOR S/'} ?? 0),
            (float) ($purchases->{'IGV S/'} ?? 0),
            (float) ($purchases->{'TOTAL S/'} ?? 0),
            (float) ($purchases->{'VALOR USD'} ?? 0),
            (float) ($purchases->{'IGV USD'} ?? 0),
            (float) ($purchases->{'TOTAL USD'} ?? 0),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FF4472C4']]], // Compañía (Azul)
            2 => ['font' => ['bold' => true, 'size' => 14]], // Reporte Nombre
            3 => ['font' => ['bold' => true, 'size' => 12, 'italic' => true]], // Fecha
            5 => ['font' => ['bold' => true]],               // Cabecera tabla
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                // Merge para los títulos
                $sheet->mergeCells("A1:{$highestColumn}1");
                $sheet->mergeCells("A2:{$highestColumn}2");
                $sheet->mergeCells("A3:{$highestColumn}3");

                // Alineación izquierda para títulos
                $sheet->getStyle("A1:A3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Congelar paneles desde fila 6
                $sheet->freezePane('A6');

                // Autofiltro para la tabla principal (fila 5 en adelante)
                if ($highestRow >= 5) {
                    $sheet->setAutoFilter("A5:{$highestColumn}{$highestRow}");
                }

                // Estilo para la cabecera de la tabla (A5)
                $headerRange = "A5:{$highestColumn}5";
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

                // Formato numérico (2 decimales) para las columnas de montos (H en adelante)
                if ($highestRow >= 6) {
                    $sheet->getStyle("H6:M{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

                    // Alineaciones
                    $sheet->getStyle("A6:G{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle("H6:M{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }
            },
        ];
    }
}

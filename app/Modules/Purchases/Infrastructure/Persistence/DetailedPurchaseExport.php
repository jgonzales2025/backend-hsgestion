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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class DetailedPurchaseExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(
        private Collection $purchases,
        private string $companyName,
        private ?string $startDate = null,
        private ?string $endDate = null
    ) {}

    public function collection(): Collection
    {
        return $this->purchases;
    }

    public function headings(): array
    {
        $date = 'Fecha: ' . now()->format('d/m/Y');
        $range = "DESDE: " . ($this->startDate ?? '---') . " HASTA: " . ($this->endDate ?? '---');

        return [
            [$this->companyName, '', '', '', '', '', '', '', '', '', $date],
            ['', '', '', '', 'REGISTRO DE COMPRAS DETALLADO'],
            ['', '', '', '', $range],
            [''], // Espacio
            [
                'EMPRESA',
                'SERIE',
                'NUMERO',
                'FECHA',
                'PROVEEDOR',
                'MOTIVO',
                'CODIGO',
                'ARTICULO',
                'MARCA',
                'CATEGORIA',
                'SUBCATEGORIA',
                'CANTIDAD',
                'T/M',
                'C.UNIT.',
                'TOT.SOLES',
                'TOT.DOLARES'
            ]
        ];
    }

    public function map($detail): array
    {
        $purchase = $detail->purchase;
        $article = $detail->article;
        $currencyId = $purchase?->currency ?? 1;
        $total = (float) ($detail->total ?? 0);

        $totalSoles = $currencyId == 1 ? number_format($total, 2, '.', '') : '';
        $totalDolares = $currencyId == 2 ? number_format($total, 2, '.', '') : '';

        return [
            $purchase?->branches?->name,
            $purchase?->reference_serie,
            $purchase?->reference_correlative,
            $purchase?->date,
            $purchase?->customers?->company_name ?: trim(($purchase?->customers?->name ?? '') . ' ' . ($purchase?->customers?->lastname ?? '') . ' ' . ($purchase?->customers?->second_lastname ?? '')),
            $this->getMotivo($purchase),
            $article?->cod_fab,
            $article?->description ?? $detail->description,
            $article?->brand?->name,
            $article?->category?->name,
            $article?->subCategory?->name ?? $article?->subcategory?->name ?? '',
            $detail->cantidad,
            $currencyId == 1 ? 'S/' : 'US$',
            number_format($detail->getPrecioCosto() ?? 0, 2, '.', ''),
            $totalSoles,
            $totalDolares,
        ];
    }

    private function getMotivo($purchase)
    {
        if ($purchase && $purchase->shoppingIncomeGuide && count($purchase->shoppingIncomeGuide) > 0) {
            $guide = $purchase->shoppingIncomeGuide[0]->entryGuide ?? null;
            return $guide?->ingressReason?->description ?? 'COMPRA';
        }
        return 'COMPRA';
    }

    public function styles(Worksheet $sheet)
    {
        // Set default font for the whole sheet
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setName('Arial');

        return [
            1 => ['font' => ['bold' => true, 'size' => 11, 'name' => 'Arial']],
            2 => ['font' => ['bold' => true, 'size' => 12, 'name' => 'Arial']],
            3 => ['font' => ['bold' => true, 'size' => 10, 'name' => 'Arial']],
            5 => ['font' => ['bold' => true, 'size' => 9, 'name' => 'Arial']],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                // Unir celdas para los títulos
                $sheet->mergeCells("E2:I2");
                $sheet->mergeCells("E3:I3");
                $sheet->getStyle("E2:E3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Estilos para la cabecera de la tabla (fila 5)
                $headerRange = "A5:{$highestColumn}5";
                $sheet->getStyle($headerRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Bordes para los datos
                if ($highestRow > 5) {
                    $sheet->getStyle("A6:{$highestColumn}{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FFCCCCCC'],
                            ],
                        ],
                    ]);
                }
            },
        ];
    }
}

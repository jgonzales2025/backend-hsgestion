<?php

namespace App\Modules\Statistics\Infrastructure\Persistence;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArticlePurchaseExport implements FromCollection, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(
        private Collection $items,
        private string $companyName,
        private string $branchName,
        private string $startDate,
        private string $endDate,
        private string $articleCode,
        private string $articleDescription
    ) {
    }

    public function collection(): Collection
    {
        // Group items by supplier and add supplier headers and subtotals
        $result = collect();
        $groupedBySupplier = $this->items->groupBy('proveedor');
        $grandTotal = 0;

        foreach ($groupedBySupplier as $supplierName => $supplierItems) {
            $supplierSubtotal = 0;

            // Add supplier header row
            $result->push([
                '',
                '',
                $supplierName ?? 'SIN PROVEEDOR',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ]);

            // Add supplier items
            foreach ($supplierItems as $item) {
                $result->push([
                    $item->sucursal,
                    $item->tipo_documento,
                    $item->serie . '-' . $item->correlativo,
                    date('d/m/Y', strtotime($item->fecha_compra)),
                    $this->articleCode,
                    $this->articleDescription,
                    (int) $item->cantidad,
                    $item->tipo_moneda,
                    (float) $item->precio_compra,
                    (float) $item->importe_soles
                ]);
                $supplierSubtotal += $item->importe_soles;
            }

            // Add supplier subtotal row
            $result->push([
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'TOTAL GENERAL',
                (float) $supplierSubtotal
            ]);

            $grandTotal += $supplierSubtotal;
        }

        // Add grand total row if there are multiple suppliers
        if ($groupedBySupplier->count() > 1) {
            $result->push([
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'TOTAL GENERAL',
                (float) $grandTotal
            ]);
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'SUC',
            'T/D',
            'NUMERO DOC.',
            'FECHA',
            'COD.ART.',
            'DESCRIPCION',
            'CANTID.',
            'T/M',
            'PRECIO',
            'SUB TOTAL'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            3 => ['font' => ['bold' => true, 'size' => 11]],
            4 => ['font' => ['size' => 10]],
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Add header information
                $sheet->insertNewRowBefore(1, 4);

                // Company name
                $sheet->setCellValue('A1', 'COMPAÑÍA : ' . strtoupper($this->companyName));
                $sheet->mergeCells('A1:F1');

                // Report title
                $sheet->setCellValue('A2', 'RELACION DE COMPRAS');
                $sheet->mergeCells('A2:J2');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A2')->getFont()->setSize(16);

                // Branch and date range
                $sheet->setCellValue('A3', 'SUCURSAL : ' . strtoupper($this->branchName));
                $sheet->setCellValue('H1', 'FECHA : ' . date('d/m/Y'));
                $sheet->mergeCells('A3:E3');

                $sheet->setCellValue('G3', 'Del ' . date('d/m/Y', strtotime($this->startDate)) . ' Al ' . date('d/m/Y', strtotime($this->endDate)));
                $sheet->setCellValue('I1', 'Hora ' . date('H:i:s') . ' p.m.');
                $sheet->mergeCells('A4:E4');

                // Add article information
                $sheet->setCellValue('A4', 'LINEA : TODOS');
                $sheet->setCellValue('G4', 'MARCA : TODOS');

                // Column headers styling
                $highestColumn = $sheet->getHighestColumn();
                $headerRange = "A5:{$highestColumn}5";

                $sheet->getStyle($headerRange)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFD9E1F2'); // Light blue
    
                $sheet->getStyle($headerRange)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Apply borders to the entire data range
                $highestRow = $sheet->getHighestRow();
                $dataRange = "A5:{$highestColumn}{$highestRow}";

                $sheet->getStyle($dataRange)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Freeze header rows
                $sheet->freezePane('A6');

                // Auto filter
                $sheet->setAutoFilter("A5:{$highestColumn}5");

                // Apply formatting to supplier headers, subtotals, and grand total
                $this->applyRowFormatting($sheet);

                // Adjust column widths
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(8);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(50);
                $sheet->getColumnDimension('G')->setWidth(12);
                $sheet->getColumnDimension('H')->setWidth(8);
                $sheet->getColumnDimension('I')->setWidth(12);
                $sheet->getColumnDimension('J')->setWidth(12);

                // Apply number format to Quantity column (Integer)
                $sheet->getStyle("G6:G{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('0');

                // Apply number format to Price and Total columns (2 decimals)
                $sheet->getStyle("I6:J{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            },
        ];
    }

    private function applyRowFormatting(Worksheet $sheet): void
    {
        $currentRow = 6; // Start after headers (row 5)
        $groupedBySupplier = $this->items->groupBy('proveedor');

        foreach ($groupedBySupplier as $supplierName => $supplierItems) {
            // Supplier header row
            $sheet->mergeCells("C{$currentRow}:J{$currentRow}");
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getFont()->setBold(true);
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getFont()->setSize(12); // Larger font
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFFFD966'); // Yellow

            // Center align the supplier header row
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            // Add border to supplier header
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_MEDIUM);

            $currentRow++;

            // Skip supplier items rows
            $currentRow += $supplierItems->count();

            // Supplier subtotal row
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getFont()->setBold(true);
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFE6F0FF'); // Light blue

            $sheet->getStyle("J{$currentRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Add top border to subtotal
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getBorders()
                ->getTop()
                ->setBorderStyle(Border::BORDER_THIN);

            $currentRow++;
        }

        // Grand total row (if exists)
        if ($groupedBySupplier->count() > 1) {
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getFont()->setBold(true);
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getFont()->setSize(12); // Larger font
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFF4B084'); // Orange/Amber

            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getFont()
                ->getColor()
                ->setARGB('FF000000'); // Black text

            $sheet->getStyle("I{$currentRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle("J{$currentRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Add double top border to grand total
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getBorders()
                ->getTop()
                ->setBorderStyle(Border::BORDER_DOUBLE);

            // Add medium outline border to grand total
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_MEDIUM);
        }
    }
}

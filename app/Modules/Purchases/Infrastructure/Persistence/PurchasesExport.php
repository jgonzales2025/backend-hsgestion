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

class PurchasesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(private Collection $purchases) {}

    public function collection(): Collection
    {
        return $this->purchases;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Proveedor',
            'RUC',
            'N° Documento',
            'Moneda',
            'Subtotal',
            'IGV',
            'Total',
            'Saldo',
            'Estado',
        ];
    }

    public function map($purchase): array
    {
        $total = (float) $purchase->getTotal();
        $saldo = (float) $purchase->getSaldo();

        $status = 'Facturado';
        if ($saldo >= $total) {
            $status = 'Pendiente';
        } elseif ($saldo > 0) {
            $status = 'En proceso';
        }

        return [
            $purchase->getId(),
            $purchase->getDate(),
            $purchase->getSupplier()->getCompanyName() ??
                trim($purchase->getSupplier()->getName() . ' ' .
                    $purchase->getSupplier()->getLastName() . ' ' .
                    $purchase->getSupplier()->getSecondLastname()),
            $purchase->getSupplier()->getDocumentNumber() ?? "",
            $purchase->getSerie() . '-' . $purchase->getCorrelative(),
            $purchase->getCurrency()->getName(),
            number_format($purchase->getSubtotal(), 2, '.', ''),
            number_format($purchase->getIgv(), 2, '.', ''),
            number_format($purchase->getTotal(), 2, '.', ''),
            number_format($purchase->getSaldo(), 2, '.', ''),
            $status,
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
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->freezePane('A2');
                $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");

                $headerRange = "A1:{$highestColumn}1";
                $sheet->getStyle($headerRange)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFE6F0FF');

                $sheet->getStyle($headerRange)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}

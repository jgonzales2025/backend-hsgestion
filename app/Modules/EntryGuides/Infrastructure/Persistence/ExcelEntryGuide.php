<?php

namespace App\Modules\EntryGuides\Infrastructure\Persistence;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelEntryGuide implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(private Collection $entryGuides) {}

    public function collection()
    {
        return $this->entryGuides;
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
    public function map($entryGuide): array
    {
        return [
            $entryGuide->getId(),
            $entryGuide->getDate(),
            $entryGuide->getSupplier() ? ($entryGuide->getSupplier()->getCompanyName() ??
                trim($entryGuide->getSupplier()->getName() . ' ' .
                    $entryGuide->getSupplier()->getLastName() . ' ' .
                    $entryGuide->getSupplier()->getSecondLastname())) : '',
            $entryGuide->getSupplier() ? $entryGuide->getSupplier()->getDocumentNumber() : '',
            $entryGuide->getSerie() . '-' . $entryGuide->getCorrelativo(),
            $entryGuide->getCurrency() ? $entryGuide->getCurrency()->getName() : '',
            number_format($entryGuide->getSubtotal(), 2, '.', ''),
            number_format($entryGuide->getIgv(), 2, '.', ''),
            number_format($entryGuide->getTotal(), 2, '.', ''),
            number_format($entryGuide->getSaldo(), 2, '.', ''),
            $entryGuide->getStatus() ? 'Activo' : 'Anulado',
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

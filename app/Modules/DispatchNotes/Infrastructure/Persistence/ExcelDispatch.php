<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelDispatch implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(private Collection $dispatchNote) {}

    public function collection(): Collection
    {
        return $this->dispatchNote;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Serie',
            'Correlativo',
            'Destinatario',
            'RUC Destinatario',
            'Motivo de Traslado',
            'Punto de Partida',
            'Punto de Llegada',
            'Peso Total',
            'Placa',
            'Conductor',
            'Estado',
        ];
    }

    public function map($dispatchNote): array
    {
        $supplier = $dispatchNote->getSupplier();
        
        return [
            $dispatchNote->getId(),
            $dispatchNote->getCreatedFecha(),
            $dispatchNote->getSerie(),
            $dispatchNote->getCorrelativo(),
            $dispatchNote->getAddressSupplier()?->getName() ?? 'N/A',
            $dispatchNote->getAddressSupplier()?->getDocumentNumber() ?? 'N/A',
            $dispatchNote->getEmissionReason() ? $dispatchNote->getEmissionReason()->getDescription() : '',
            $dispatchNote->getBranch() ? $dispatchNote->getBranch()->getAddress() : '',
            (function ()use($dispatchNote) {
                $code = EloquentCustomerAddress::where('id', $dispatchNote->getdestination_branch_client())->first();
                if ($code) {
                    return $code->address;
                }
                return 'N/A';
            })(),
            $dispatchNote?->getTotalWeight() ?? 'N/A',
            $dispatchNote->getLicensePlate() ? $dispatchNote->getLicensePlate() : 'N/A',
            $dispatchNote?->getConductor()?->getName() ?? $dispatchNote?->getTransport()?->getCompanyName(),
            $dispatchNote?->getStatus() ? 'Activo' : 'Anulado',
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

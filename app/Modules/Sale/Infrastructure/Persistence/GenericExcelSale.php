<?php

namespace App\Modules\Sale\Infrastructure\Persistence;

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

class GenericExcelSale implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    public function __construct(private Collection $sales) {}

    public function collection(): Collection
    {
        return $this->sales;
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

    /**
     * @param mixed $sale
     */
    public function map($sale): array
    {
        // Convertimos a array para manejar tanto objetos stdClass como arrays asociativos
        $data = is_object($sale) ? get_object_vars($sale) : (array)$sale;

        // Limpiamos los valores de caracteres nulos
        $cleanData = array_map(function ($value) {
            if ($value === null) return '';
            if (is_string($value)) {
                $value = str_replace("\0", '', $value);
                $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
            }
            return $value;
        }, $data);

        // Mapeamos según los nombres de las columnas que devuelve el SP 'sp_registro_ventas_compras'
        // NOTA: Ajusta estos nombres si el SP usa otros diferentes (ej. 'Serie' en lugar de 'serie')
        return [
            $cleanData['id'] ?? $cleanData['ID'] ?? '',
            $cleanData['serie'] ?? $cleanData['SERIE'] ?? '',
            $cleanData['correlativo'] ?? $cleanData['CORRELATIVO'] ?? '',
            $cleanData['fecha'] ?? $cleanData['FECHA'] ?? '',
            $cleanData['nrodoc_cli_pro'] ?? $cleanData['NRODOC_CLI_PRO'] ?? '',
            $cleanData['razon_social'] ?? $cleanData['RAZON_SOCIAL'] ?? '',
            $cleanData['tipo_cambio'] ?? $cleanData['TIPO_CAMBIO'] ?? '',
            $cleanData['valor_soles'] ?? $cleanData['VALOR_SOLES'] ?? '',
            $cleanData['igv_soles'] ?? $cleanData['IGV_SOLES'] ?? '',
            $cleanData['total_soles'] ?? $cleanData['TOTAL_SOLES'] ?? '',
            $cleanData['valor_usd'] ?? $cleanData['VALOR_USD'] ?? '',
            $cleanData['igv_usd'] ?? $cleanData['IGV_USD'] ?? '',
            $cleanData['total_usd'] ?? $cleanData['TOTAL_USD'] ?? '',
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

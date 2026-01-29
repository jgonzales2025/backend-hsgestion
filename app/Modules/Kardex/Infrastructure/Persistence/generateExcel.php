<?php

namespace App\Modules\Kardex\Infrastructure\Persistence;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class GenerateExcel implements FromArray, WithHeadings, WithEvents, ShouldAutoSize, WithStyles
{
    private array $rows = [];
    private array $keys = [];

    public function __construct(
        private readonly ?int $companyId,
        private readonly ?int $branchId,
        private readonly ?int $productId,
        private readonly ?string $fecha,
        private readonly ?string $fecha1,
        private readonly ?int $categoria,
        private readonly ?int $marca,
        private readonly ?int $consulta,
        private readonly string $title = 'Kardex Físico',
        private readonly string $companyName = ''
    ) {
        $results = DB::select(
            'CALL sp_kardex_fisico(?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $this->companyId,
                $this->branchId,
                $this->productId,
                $this->fecha,
                $this->fecha1,
                $this->categoria,
                $this->marca,
                $this->consulta,
            ]
        );
        $this->rows = array_map(fn($row) => (array) $row, $results);
        $this->keys = empty($this->rows) ? [] : array_keys($this->rows[0]);
    }

    public function array(): array
    {
        return array_map(fn($row) => array_values($row), $this->rows);
    }

    public function headings(): array
    {
        if (empty($this->keys)) {
            return [];
        }

        $dateRange = 'FECHA EMISIÓN: ' . now()->format('d/m/Y');
        if ($this->fecha && $this->fecha1) {
            $dateRange .= '   RANGO: ' . date('d/m/Y', strtotime($this->fecha)) . ' - ' . date('d/m/Y', strtotime($this->fecha1));
        }

        // Map technical keys to more user-friendly uppercase names
        $cleanKeys = array_map(function ($key) {
            $mapped = match (strtolower($key)) {
                'producto_id_producto' => 'ID PRODUCTO',
                'desart' => 'DESCRIPCION PRODUCTO',
                'id_kardex' => 'ID',
                'fecha_emision' => 'FECHA',
                default => str_replace('_', ' ', $key)
            };
            return mb_strtoupper($mapped);
        }, $this->keys);

        return [
            [$this->companyName],
            [mb_strtoupper($this->title)],
            [$dateRange],
            [''], // Espacio
            $cleanKeys
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
                if ($this->isEmpty()) {
                    return;
                }
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                // Merge para los títulos
                $sheet->mergeCells("A1:{$highestColumn}1");
                $sheet->mergeCells("A2:{$highestColumn}2");
                $sheet->mergeCells("A3:{$highestColumn}3");

                // Alineación izquierda para títulos
                $sheet->getStyle("A1:A3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Ajustar altura de filas
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(25);
                $sheet->getRowDimension(3)->setRowHeight(20);
                $sheet->getRowDimension(5)->setRowHeight(25);

                // Estilo para la cabecera de la tabla (A5)
                $headerRange = "A5:{$highestColumn}5";
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size'  => 11,
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

                // Bordes para los datos
                if ($highestRow >= 6) {
                    $dataRange = "A5:{$highestColumn}" . $highestRow;
                    $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFBFBFBF');

                    // Congelar paneles
                    $sheet->freezePane('A6');

                    // Autofiltro
                    $sheet->setAutoFilter($headerRange);
                }
            },
        ];
    }

    public function isEmpty(): bool
    {
        return empty($this->rows);
    }
}

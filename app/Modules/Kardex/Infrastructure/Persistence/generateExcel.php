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

class GenerateExcel implements FromArray, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize
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
        private readonly string $title = 'Kardex Físico'
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
        return $this->keys;
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $sheet->getHighestDataColumn();
                $sheet->setCellValue('A1', $this->title);
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(1)->setRowHeight(24);
                $headerRange = "A3:{$lastColumn}3";
                $sheet->getStyle($headerRange)->getFont()->setBold(true);
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE6E6E6');
                $dataRange = "A3:{$lastColumn}" . $sheet->getHighestRow();
                $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFBFBFBF');
                $sheet->freezePane('A4');
                $sheet->setAutoFilter($headerRange);
            },
        ];
    }
}

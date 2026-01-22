<?php

namespace App\Modules\Statistics\Infrastructure\Persistence;

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

class ListaPreciosHeaderExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    private array $columns = [];

    public function __construct(
        private array $data,
        private string $companyName
    ) {
        $this->columns = $this->buildColumns($data);
    }

    private function buildColumns(array $data): array
    {
        $cols = [];
        foreach ($data as $item) {
            if (is_object($item)) {
                $item = json_decode(json_encode($item), true);
            } elseif (!is_array($item)) {
                $item = (array) $item;
            }
            foreach (array_keys($item) as $key) {
                if (!in_array((string) $key, $cols, true)) {
                    $cols[] = (string) $key;
                }
            }
        }
        return $cols;
    }

    public function collection(): Collection
    {
        return collect($this->data)->map(function ($item) {
            if (is_object($item)) {
                $item = json_decode(json_encode($item), true);
            } elseif (!is_array($item)) {
                $item = (array) $item;
            }

            return array_map(function ($value) {
                if ($value === null) return '';

                if (is_string($value)) {
                    $value = str_replace("\0", '', $value);
                    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);

                    if (!mb_check_encoding($value, 'UTF-8')) {
                        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    }
                }

                return $value;
            }, $item);
        });
    }

    public function headings(): array
    {
        $date = 'FECHA EMISIÓN: ' . now()->format('d/m/Y');

        return [
            [$this->companyName],
            ['REPORTE DE LISTA DE PRECIOS'],
            [$date],
            [''], // Espacio
            $this->columns // Cabecera de la tabla
        ];
    }

    public function map($row): array
    {
        $mapped = [];
        foreach ($this->columns as $col) {
            $val = $row[$col] ?? '';
            if ($val === null) {
                $val = '';
            }
            if (is_string($val)) {
                $val = str_replace("\0", '', $val);
                $val = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $val);
                if (!mb_check_encoding($val, 'UTF-8')) {
                    $val = mb_convert_encoding($val, 'UTF-8', 'ISO-8859-1');
                }
            } elseif (is_bool($val)) {
                $val = $val ? 1 : 0;
            } elseif (is_array($val) || is_object($val)) {
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);
            }
            $mapped[] = $val;
        }
        return $mapped;
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
                $sheet->getStyle($headerRange)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FF4472C4'); // Azul

                $sheet->getStyle($headerRange)
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFFFF'); // Blanco

                $sheet->getStyle($headerRange)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}

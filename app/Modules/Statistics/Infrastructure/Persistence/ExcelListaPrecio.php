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

class ExcelListaPrecio implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    private array $columns = [];
    public function __construct(private array $data) {
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
        // Convertir array a colección y sanitizar datos
        return collect($this->data)->map(function ($item) {
            // Convertir objeto a array usando JSON (evita caracteres nulos)
            if (is_object($item)) {
                $item = json_decode(json_encode($item), true);
            } elseif (!is_array($item)) {
                $item = (array) $item;
            }

            // Limpiar cada valor
            return array_map(function ($value) {
                if ($value === null) return '';

                if (is_string($value)) {
                    // Remover caracteres nulos y de control
                    $value = str_replace("\0", '', $value);
                    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);

                    // Validar UTF-8
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
        return $this->columns;
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
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Congelar la fila de cabecera
                $sheet->freezePane('A2');

                // Aplicar autofiltro desde la cabecera hasta la última fila
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");

                // Estilo de cabecera: fondo y alineación
                $headerRange = "A1:{$highestColumn}1";
                $sheet->getStyle($headerRange)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FF4472C4'); // azul

                $sheet->getStyle($headerRange)
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFFFF'); // texto blanco

                $sheet->getStyle($headerRange)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}

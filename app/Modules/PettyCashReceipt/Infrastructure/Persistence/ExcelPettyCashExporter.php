<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Persistence;

use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashExporterInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExcelPettyCashExporter implements PettyCashExporterInterface
{
    public function export(array $data): string
    {
        $fileName = 'parte_caja_' . now()->format('Y-m-d_His') . '.xlsx';
        $filePath = 'exports/' . $fileName;

        Storage::disk('public')->makeDirectory('exports');

        $stored = Excel::store(
            new PettyCashProcedureExport($data),
            $filePath,
            'public',
            \Maatwebsite\Excel\Excel::XLSX
        );

        if (!$stored) {
            throw new \RuntimeException('No se pudo almacenar el archivo XLSX');
        }

        return $filePath;
    }
}

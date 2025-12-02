<?php

namespace App\Modules\PettyCashReceipt\Application\UseCases;

use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashExporterInterface;

class ExportPettyCashToExcelUseCase
{
    public function __construct(
        private readonly PettyCashExporterInterface $exporter
    ) {}

    /**
     * Exporta los datos de caja chica a un archivo Excel.
     * 
     * @param array $data Datos a exportar
     * @return array ['success' => bool, 'filepath' => string|null, 'error' => string|null]
     */
    public function execute(array $data): array
    {
        try {
            // Validar que hay datos para exportar
            if (empty($data)) {
                return [
                    'success' => false,
                    'filepath' => null,
                    'error' => 'No hay datos para exportar'
                ];
            }

            // Exportar usando la interfaz
            $filePath = $this->exporter->export($data);

            // Obtener la ruta completa del archivo
            $fullPath = storage_path('app/public/' . $filePath);

            // Verificar que el archivo existe
            if (!file_exists($fullPath)) {
                return [
                    'success' => false,
                    'filepath' => null,
                    'error' => 'Error al generar el archivo'
                ];
            }

            return [
                'success' => true,
                'filepath' => $fullPath,
                'filename' => basename($filePath),
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'filepath' => null,
                'error' => $e->getMessage()
            ];
        }
    }
}

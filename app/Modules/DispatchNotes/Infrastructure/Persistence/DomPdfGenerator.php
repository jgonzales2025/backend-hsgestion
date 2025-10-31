<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;

use App\Modules\DispatchNotes\Domain\Interfaces\PdfGeneratorInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DomPdfGenerator implements PdfGeneratorInterface
{
    public function generate(DispatchNote $dispatchNote): string
    {
        try {
            // Cargar tu vista Blade con los datos de la guía de remisión
            $pdf = Pdf::loadView('invoice', [
                'dispatchNote' => $dispatchNote
            ]);


            $filename = 'dispatch_note_' . $dispatchNote->getId() . '.pdf';
            $path = 'pdf/' . $filename;

            // Guardar el PDF en storage/app/public/pdf/
            Storage::disk('public')->put($path, $pdf->output());

            return $path;
        } catch (\Throwable $e) {
            Log::error('Error generando PDF: ' . $e->getMessage(), [
                'dispatch_note_id' => $dispatchNote->getId(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    public function exists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }

    public function getUrl(string $path): string
    {
        return asset('storage/' . $path);
    }
}

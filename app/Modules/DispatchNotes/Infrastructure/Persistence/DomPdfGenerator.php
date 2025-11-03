<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\PdfGeneratorInterface;
use App\Modules\DispatchNotes\Infrastructure\Resource\DispatchNoteResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DomPdfGenerator implements PdfGeneratorInterface
{
    public function generate(DispatchNote $dispatchNote): string
    {
        try {
            // Obtener los artículos asociados a esta guía
            $dispatchArticles = (function () use ($dispatchNote) {
                try {
                    $articles = app(DispatchArticleRepositoryInterface::class)
                        ->findById($dispatchNote->getId());

                    return DispatchArticleResource::collection($articles)->resolve();
                } catch (\Throwable $e) {
                    \Log::error("Error obteniendo artículos: " . $e->getMessage());
                    return [];
                }
            })();
            $dispatch = (function () use ($dispatchNote) {
                try {
                    $note = app(DispatchNotesRepositoryInterface::class)
                        ->findById($dispatchNote->getId());

                    // Si es una sola entidad, se usa 'make' en vez de 'collection'
                    return (new DispatchNoteResource($note))->resolve();
                } catch (\Throwable $e) {
                    \Log::error("Error obteniendo guia de remision: " . $e->getMessage());
                    return [];
                }
            })();
            // Cargar la vista Blade con los datos de la guía y los artículos
            $pdf = Pdf::loadView('invoice', [
                'dispatchNote' => $dispatchNote,
                'dispatchArticles' => $dispatchArticles,
            ]);

            // Generar el nombre y la ruta del PDF
            $filename = 'dispatch_note_' . $dispatchNote->getId() . '.pdf';
            $path = 'pdf/' . $filename;

            // Guardar el PDF en storage/app/public/pdf/
            Storage::disk('public')->put($path, $pdf->output());

            return $path;

        } catch (\Throwable $e) {
            \Log::error('Error generando PDF: ' . $e->getMessage(), [
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

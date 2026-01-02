<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\PdfGeneratorInterface;
use App\Modules\DispatchNotes\Infrastructure\Resource\ExcelNoteResource;
use Barryvdh\DomPDF\Facade\Pdf;
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

                    $serialsByArticle = app(\App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface::class)
                        ->findSerialsByTransferOrderId($dispatchNote->getId());

                    $articles = array_map(function ($article) use ($serialsByArticle) {
                        $article->serials = $serialsByArticle[$article->getArticleID()] ?? [];
                        return $article;
                    }, $articles);

                    return DispatchArticleResource::collection($articles)->resolve();
                } catch (\Throwable $e) {
                    Log::error("Error obteniendo artículos: " . $e->getMessage());
                    return [];
                }
            })();

            // Transformar la entidad a array usando el recurso
            $dispatchNoteData = (new ExcelNoteResource($dispatchNote))->resolve();

            // Generar QR code con información de la guía
            $qrData = sprintf(
                "%s|%s|%s-%s|%s|%s",
                $dispatchNoteData['company']['ruc'] ?? '',
                $dispatchNoteData['customer']['ruc'] ?? '',
                $dispatchNoteData['serie'] ?? '',
                str_pad($dispatchNoteData['correlativo'] ?? '', 8, '0', STR_PAD_LEFT),
                $dispatchNoteData['date'] ?? '',
                number_format(array_sum(array_column($dispatchArticles, 'subtotal_weight')), 2)
            );

            // Generar QR code usando SimpleSoftwareIO\QrCode (SVG para evitar dependencia de imagick)
            $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(150)
                ->margin(0)
                ->generate($qrData));

            // Cargar la vista Blade con los datos de la guía y los artículos
            $pdf = Pdf::loadView('dispatch_note', [
                'dispatchNote' => $dispatchNoteData,
                'dispatchArticles' => $dispatchArticles,
                'qrCode' => $qrCode,
            ]);

            // Retornar el contenido del PDF directamente
            return $pdf->output();
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
        return false; // No usamos almacenamiento
    }

    public function getUrl(string $path): string
    {
        return ''; // No usamos URLs
    }
}

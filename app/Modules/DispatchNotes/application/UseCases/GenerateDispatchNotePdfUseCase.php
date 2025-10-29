<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Services\PdfGeneratorInterface;

class GenerateDispatchNotePdfUseCase
{
    public function __construct(
        private readonly DispatchNotesRepositoryInterface $repository,
        private readonly PdfGeneratorInterface $pdfGenerator
    ) {
    }

    public function execute(int $id): string
    {
        $dispatchNote = $this->repository->findById($id);

        if (!$dispatchNote) {
            throw new \Exception('Guía de remisión no encontrada');
        }

        // Generar nombre del archivo
        $filename = 'dispatch_note_' . $dispatchNote->getId() . '.pdf';
        $path = 'pdf/' . $filename;

        // Verificar si ya existe el PDF
        if (!$this->pdfGenerator->exists($path)) {
            $path = $this->pdfGenerator->generate($dispatchNote);
        }

        return $this->pdfGenerator->getUrl($path);
    }
}
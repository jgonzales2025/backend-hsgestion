<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\PdfGeneratorInterface;

class GenerateDispatchNotePdfUseCase
{
    public function __construct(
        private readonly DispatchNotesRepositoryInterface $repository,
        private readonly PdfGeneratorInterface $pdfGenerator
    ) {}

    public function execute(int $id): string
    {
        $dispatchNote = $this->repository->findById($id);

        if (!$dispatchNote) {
            throw new \Exception('Guía de remisión no encontrada');
        }

        // Generar y retornar el contenido del PDF directamente
        return $this->pdfGenerator->generate($dispatchNote);
    }
}

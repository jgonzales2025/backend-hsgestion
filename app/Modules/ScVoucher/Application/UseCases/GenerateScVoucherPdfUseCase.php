<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;
use App\Modules\ScVoucher\Domain\Interface\PdfGeneratorInterface;

class GenerateScVoucherPdfUseCase
{
    public function __construct(
        private readonly ScVoucherRepositoryInterface $repository,
        private readonly PdfGeneratorInterface $pdfGenerator
    ) {}

    public function execute(int $id): string
    {
        $scVoucher = $this->repository->findById($id);

        if (!$scVoucher) {
            throw new \Exception('Voucher no encontrado');
        }

        // Generar y retornar el contenido del PDF directamente
        return $this->pdfGenerator->generate($scVoucher);
    }
}

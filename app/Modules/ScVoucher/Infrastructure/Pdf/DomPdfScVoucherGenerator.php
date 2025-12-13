<?php

namespace App\Modules\ScVoucher\Infrastructure\Pdf;

use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucher\Domain\Interface\PdfGeneratorInterface;
use App\Modules\ScVoucher\Infrastructure\Resource\ScVoucherResource;
use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;
use App\Modules\ScVoucherdet\Infrastructure\Resource\ScVoucherdetResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DomPdfScVoucherGenerator implements PdfGeneratorInterface
{
    public function generate(ScVoucher $scVoucher): string
    {
        try {
            // Fetch details
            $scVoucherDets = (function () use ($scVoucher) {
                try {
                    $details = app(ScVoucherdetRepositoryInterface::class)
                        ->findByVoucherId($scVoucher->getId());

                    return ScVoucherdetResource::collection($details)->resolve();
                } catch (\Throwable $e) {
                    Log::error("Error fetching ScVoucher details: " . $e->getMessage());
                    return [];
                }
            })();

            // Transform main entity
            $scVoucherData = (new ScVoucherResource($scVoucher))->resolve();

            // Load view
            $pdf = Pdf::loadView('sc_voucher_pdf', [
                'scVoucher' => $scVoucherData,
                'details' => $scVoucherDets,
            ]);

            return $pdf->output();
        } catch (\Throwable $e) {
            Log::error('Error generating PDF: ' . $e->getMessage(), [
                'sc_voucher_id' => $scVoucher->getId(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \RuntimeException('No se pudo generar el PDF: ' . $e->getMessage());
        }
    }
}

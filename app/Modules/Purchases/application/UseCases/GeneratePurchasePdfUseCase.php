<?php

namespace App\Modules\Purchases\Application\UseCases;

use App\Modules\DetailPurchaseGuides\Domain\Interface\DetailPurchaseGuideRepositoryInterface;
use App\Modules\DetailPurchaseGuides\Infrastructure\Resource\DetailPurchaseGuideResource;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Resource\PurchaseResource;
use App\Modules\Purchases\Domain\Interface\GeneratepdfRepositoryInterface;
use App\Modules\ShoppingIncomeGuide\Domain\Interface\ShoppingIncomeGuideRepositoryInterface;
use Illuminate\Http\Response;

class GeneratePurchasePdfUseCase
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly DetailPurchaseGuideRepositoryInterface $detailPurchaseGuideRepository,
        private readonly ShoppingIncomeGuideRepositoryInterface $shoppingIncomeGuideRepository,
        private readonly GeneratepdfRepositoryInterface $pdfGenerator,
    ) {}

    public function execute(int $id): Response
    {
        $purchase = $this->purchaseRepository->findById($id);
        if (!$purchase) {
            throw new \RuntimeException('Compra no encontrada');
        }

        $details = $this->detailPurchaseGuideRepository->findById($purchase->getId());
        $shopping = $this->shoppingIncomeGuideRepository->findById($purchase->getId());
        $entryGuideIds = array_map(fn($item) => $item->getEntryGuideId(), $shopping);

        $html = view('purchase_pdf', [
            'purchase' => (new PurchaseResource($purchase))->resolve(),
            'details' => DetailPurchaseGuideResource::collection($details)->resolve(),
            'entry_guide' => $entryGuideIds,
        ])->render();

        $filename = 'purchase_' . $purchase->getId() . '.pdf';
        return $this->pdfGenerator->download($html, $filename, ['orientation' => 'portrait']);
    }
}


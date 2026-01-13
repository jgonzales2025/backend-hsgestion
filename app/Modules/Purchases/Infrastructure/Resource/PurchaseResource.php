<?php

namespace App\Modules\Purchases\Infrastructure\Resource;

use App\Modules\DetailPurchaseGuides\Infrastructure\Resource\DetailPurchaseGuideResource;
use App\Modules\ShoppingIncomeGuide\Infrastructure\Resource\ShoppingIncomeGuideResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currencyId = $this->resource->getCurrency()->getId();
        return [
            'id' => $this->resource->getId(),
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
            ],
            'supplier' => [
                'id' => $this->resource->getSupplier()->getId(),
                'name' => $this->resource->getSupplier()->getCompanyName() ??
                    trim($this->resource->getSupplier()->getName() . ' ' .
                        $this->resource->getSupplier()->getLastName() . ' ' .
                        $this->resource->getSupplier()->getSecondLastname()),
                'ruc' => $this->resource->getSupplier()->getDocumentNumber() ?? "",
            ],
            'supplierdos' => [
                'id' => $this->resource->getSupplier()->getId(),
                'name' => ($this->resource->getSupplier()->getCompanyName() ??
                    trim($this->resource->getSupplier()->getName() . ' ' .
                        $this->resource->getSupplier()->getLastName() . ' ' .
                        $this->resource->getSupplier()->getSecondLastname())) . " " . $this->resource->getSupplier()->getDocumentNumber() . " " . $this->getSupplierAddress(),

            ],
            'serie' => $this->resource->getSerie(),
            'correlative' => $this->resource->getCorrelative(),
            'exchange_type' => $this->resource->getExchangeType(),
            'paymentType' => [
                'id' => $this->resource->getPaymentType()->getId(),
                'name' => $this->resource->getPaymentType()?->getName(),
            ],
            'currency' => [
                'id' => $this->resource->getCurrency()->getId(),
                'name' => $this->resource->getCurrency()->getName(),
            ],
            'date' => $this->resource->getDate(),
            'date_ven' => $this->resource->getDateVen(),
            'days' => $this->resource->getDays(),
            'observation' => $this->resource->getObservation(),
            'detraccion' => $this->resource->getDetraccion(),
            'fech_detraccion' => $this->resource->getFechDetraccion(),
            'amount_detraccion' => $this->resource->getAmountDetraccion(),
            'is_detracion' => $this->resource->getIsDetracion(),
            'subtotal' => $this->resource->getSubtotal(),
            'total_desc' => $this->resource->getTotalDesc(),
            'inafecto' => $this->resource->getInafecto(),
            'igv' => $this->resource->getIgv(),
            'total' => $this->resource->getTotal(),
            'is_igv' => $this->resource->getIsIgv(),
            'reference_document_type' => [
                'id' => $this->resource->getTypeDocumentId()->getId(),
                'description' => $this->resource->getTypeDocumentId()->getDescription(),
                'name' => $this->resource->getTypeDocumentId()?->getAbbreviation(),
            ],
            'reference_serie' => $this->resource->getReferenceSerie(),
            'reference_correlative' => $this->resource->getReferenceCorrelative(),
            'saldo_soles' => $currencyId == 1 ? $this->resource->getSaldo() : (float)number_format($this->resource->getSaldo() * $this->resource->getExchangeType(), 4),
            'saldo_dolares' => $currencyId == 2 ? $this->resource->getSaldo() : (float)number_format($this->resource->getSaldo() / $this->resource->getExchangeType(), 4),
            'process_status' => $this->calculateProcessStatus(),
            'nc_document_id' => $this->resource?->getNcDocumentId(),
            'nc_reference_serie' => $this->resource?->getNcReferenceSerie(),
            'nc_reference_correlative' => $this->resource?->getNcReferenceCorrelative(),

            'det_compras_guia_ingreso' =>  DetailPurchaseGuideResource::collection($this->resource->getDetComprasGuiaIngreso()),
            'entry_guide' => array_map(fn($item) => $item->getEntryGuideId(), $this->resource->getShoppingIncomeGuide()),
            'pdf_base64' => $this->generatePdfBase64($request),
        ];
    }

    private function calculateProcessStatus(): string
    {
        $total = $this->resource->getTotal();
        $saldo = $this->resource->getSaldo();

        if ($saldo >= $total) {
            return 'pendiente';
        }
        if ($saldo > 0) {
            return 'en proceso';
        }
        return 'facturado';
    }

    private function getSupplierAddress(): string
    {
        $addresses = $this->resource->getSupplier()->getAddresses() ?? [];
        foreach ($addresses as $address) {
            return $address->getAddress();
        }
        return !empty($addresses) ? $addresses[0]->getAddress() : "";
    }

    private function generatePdfBase64(Request $request): ?string
    {
        // Solo generar base64 si es una petición individual para no afectar el rendimiento del listado
        // O si se solicita explícitamente vía query param
        if ($request->routeIs('*.show') || $request->routeIs('*.store') || $request->routeIs('*.update') || $request->has('include_pdf')) {
            try {
                $companyRepository = app(\App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface::class);
                $company = $companyRepository->findById($this->resource->getCompanyId());

                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('purchase_pdf', [
                    'purchase' => $this->resource,
                    'company' => $company,
                ]);

                return base64_encode($pdf->output());
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }
}

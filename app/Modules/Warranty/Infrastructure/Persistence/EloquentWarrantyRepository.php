<?php

namespace App\Modules\Warranty\Infrastructure\Persistence;

use App\Modules\Warranty\Domain\Entities\TechnicalSupport;
use App\Modules\Warranty\Domain\Entities\UpdateWarranty;
use App\Modules\Warranty\Domain\Entities\Warranty;
use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;
use App\Modules\Warranty\Infrastructure\Model\EloquentWarranty;
use Carbon\Carbon;

class EloquentWarrantyRepository implements WarrantyRepositoryInterface
{
    public function findAll(?string $description, ?string $startDate, ?string $endDate, ?int $warrantyStatusId)
    {
        $eloquentWarranties = EloquentWarranty::query()
        ->when($description, fn($query) 
            => $query->whereHas('customer', fn($query) => $query->where('company_name', 'like', "%{$description}%")
                ->orWhere('name', 'like', "%{$description}%")
                ->orWhere('lastname', 'like', "%{$description}%")
                ->orWhere('second_lastname', 'like', "%{$description}%"))
            ->orWhereHas('article', fn($query) => $query->where('description', 'like', "%{$description}%"))
            ->orWhere('serie_art', 'like', "%{$description}%")
            ->orWhere('correlative', 'like', "%{$description}%"))
        ->when($startDate && $endDate, fn($query) => $query->whereBetween('date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]))
        ->when($warrantyStatusId, fn($query) => $query->where('warranty_status_id', $warrantyStatusId))
        ->orderBy('id', 'desc')
        ->paginate(10);

        $eloquentWarranties->getCollection()->transform(fn($warranty) => $this->mapByDocumentType($warranty))->toArray();
        return $eloquentWarranties;
    }

    public function save(Warranty $warranty): int
    {
        return EloquentWarranty::create([
            "document_type_warranty_id" => $warranty->document_type_warranty_id,
            "company_id" => $warranty->getCompany()->getId(),
            "branch_id" => $warranty->getBranch()->getId(),
            "branch_sale_id" => $warranty->getBranchSale()->getId(),
            "serie" => $warranty->serie,
            "correlative" => $warranty->correlative,
            "article_id" => $warranty->getArticle()->getId(),
            "serie_art" => $warranty->serie_art,
            "date" => $warranty->date,
            "reference_sale_id" => $warranty->getReferenceSale()->getId(),
            "customer_id" => $warranty->getCustomer()->getId(),
            "customer_phone" => $warranty->customer_phone,
            "customer_email" => $warranty->customer_email,
            "failure_description" => $warranty->failure_description,
            "observations" => $warranty->observations,
            "diagnosis" => $warranty->diagnosis,
            "supplier_id" => $warranty->getSupplier()->getId(),
            "entry_guide_id" => $warranty->getEntryGuide()->getId(),
            "contact" => $warranty->contact,
            "follow_up_diagnosis" => $warranty->follow_up_diagnosis,
            "follow_up_status" => $warranty->follow_up_status,
            "solution" => $warranty->solution,
            "warranty_status_id" => $warranty->getWarrantyStatus()->getId(),
            "solution_date" => $warranty->solution_date,
            "delivery_description" => $warranty->delivery_description,
            "delivery_serie_art" => $warranty->delivery_serie_art,
            "credit_note_serie" => $warranty->credit_note_serie,
            "credit_note_correlative" => $warranty->credit_note_correlative,
            "delivery_date" => $warranty->delivery_date,
            "dispatch_note_serie" => $warranty->dispatch_note_serie,
            "dispatch_note_correlative" => $warranty->dispatch_note_correlative,
            "dispatch_note_date" => $warranty->dispatch_note_date
        ])->id;
    }

    public function findById(int $id)
    {
        $warranty = EloquentWarranty::find($id);
        return $warranty ? $this->mapByDocumentType($warranty) : null;
    }

    public function getLastDocumentNumber(string $serie): ?string
    {
        $warranty = EloquentWarranty::where('serie', $serie)
            ->orderBy('correlative', 'desc')
            ->first();

        return $warranty?->correlative;
    }

    public function saveTechnicalSupport(TechnicalSupport $technicalSupport): int
    {
        return EloquentWarranty::create([
            "document_type_warranty_id" => $technicalSupport->getDocumentTypeWarrantyId(),
            "company_id" => $technicalSupport->getCompany()->getId(),
            "branch_id" => $technicalSupport->getBranch()->getId(),
            "serie" => $technicalSupport->getSerie(),
            "correlative" => $technicalSupport->getCorrelative(),
            "date" => $technicalSupport->getDate(),
            "customer_phone" => $technicalSupport->getCustomerPhone(),
            "customer_email" => $technicalSupport->getCustomerEmail(),
            "failure_description" => $technicalSupport->getFailureDescription(),
            "observations" => $technicalSupport->getObservations(),
            "diagnosis" => $technicalSupport->getDiagnosis(),
            "contact" => $technicalSupport->getContact()
        ])->id;
    }
    
    public function updateWarranty(UpdateWarranty $updateWarranty, int $id): ?int
    {
	    return EloquentWarranty::where('id', $id)->update([
            "customer_email" => $updateWarranty->getCustomerEmail(),
            "failure_description" => $updateWarranty->getFailureDescription(),
            "observations" => $updateWarranty->getObservations(),
            "diagnosis" => $updateWarranty->getDiagnosis(),
            "follow_up_diagnosis" => $updateWarranty->getFollowUpDiagnosis(),
            "follow_up_status" => $updateWarranty->getFollowUpStatus(),
            "solution" => $updateWarranty->getSolution(),
            "solution_date" => $updateWarranty->getSolutionDate(),
            "delivery_description" => $updateWarranty->getDeliveryDescription(),
            "delivery_serie_art" => $updateWarranty->getDeliverySerieArt(),
            "credit_note_serie" => $updateWarranty->getCreditNoteSerie(),
            "credit_note_correlative" => $updateWarranty->getCreditNoteCorrelative(),
            "delivery_date" => $updateWarranty->getDeliveryDate(),
            "dispatch_note_serie" => $updateWarranty->getDispatchNoteSerie(),
            "dispatch_note_correlative" => $updateWarranty->getDispatchNoteCorrelative(),
            "dispatch_note_date" => $updateWarranty->getDispatchNoteDate()
        ]);
        
        return $id;
    }

    private function mapByDocumentType(EloquentWarranty $warranty)
    {
        return match($warranty->document_type_warranty_id) {
            1 => $this->mapToEntity($warranty),
            2 => $this->mapToEntityTechnicalSupport($warranty),
            default => $this->mapToEntity($warranty)
        };
    }

    private function mapToEntity(EloquentWarranty $warranty): Warranty
    {
        return new Warranty(
            $warranty->id,
            $warranty->document_type_warranty_id,
            $warranty->company->toDomain($warranty->company),
            $warranty->branch->toDomain($warranty->branch),
            $warranty->branch_sale->toDomain($warranty->branch_sale),
            $warranty->serie,
            $warranty->correlative,
            $warranty->article->toDomain($warranty->article),
            $warranty->serie_art,
            $warranty->date,
            $warranty->reference_sale->toDomain($warranty->reference_sale),
            $warranty->customer->toDomain($warranty->customer),
            $warranty->customer_phone,
            $warranty->customer_email,
            $warranty->failure_description,
            $warranty->observations,
            $warranty->diagnosis,
            $warranty->supplier->toDomain($warranty->supplier),
            $warranty->entry_guide->toDomain($warranty->entry_guide),
            $warranty->contact,
            $warranty->follow_up_diagnosis,
            $warranty->follow_up_status,
            $warranty->solution,
            $warranty->warranty_status->toDomain($warranty->warranty_status),
            $warranty->solution_date,
            $warranty->delivery_description,
            $warranty->delivery_serie_art,
            $warranty->credit_note_serie,
            $warranty->credit_note_correlative,
            $warranty->delivery_date,
            $warranty->dispatch_note_serie,
            $warranty->dispatch_note_correlative,
            $warranty->dispatch_note_date
        );
    }

    private function mapToEntityTechnicalSupport(EloquentWarranty $warranty): TechnicalSupport
    {
        return new TechnicalSupport(
            $warranty->id,
            $warranty->document_type_warranty_id,
            $warranty->company->toDomain($warranty->company),
            $warranty->branch->toDomain($warranty->branch),
            $warranty->serie,
            $warranty->correlative,
            $warranty->date,
            $warranty->customer_phone,
            $warranty->customer_email,
            $warranty->failure_description,
            $warranty->observations,
            $warranty->diagnosis,
            $warranty->contact
        );
    }
}
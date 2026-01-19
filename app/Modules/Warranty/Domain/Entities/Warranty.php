<?php

namespace App\Modules\Warranty\Domain\Entities;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\WarrantyStatus\Domain\Entities\WarrantyStatus;

class Warranty
{
    public int $id;
    public int $document_type_warranty_id;
    public Company $company;
    public Branch $branch;
    public Branch $branch_sale;
    public string $serie;
    public string $correlative;
    public Article $article;
    public ?string $serie_art;
    public string $date;
    public Sale $reference_sale;
    public Customer $customer;
    public ?string $customer_phone;
    public ?string $customer_email;
    public ?string $failure_description;
    public ?string $observations;
    public ?string $diagnosis;
    public Customer $supplier;
    public EntryGuide $entry_guide;
    public ?string $contact;
    public ?string $follow_up_diagnosis;
    public ?string $follow_up_status;
    public ?string $solution;
    public WarrantyStatus $warranty_status;
    public ?string $solution_date;
    public ?string $delivery_description;
    public ?string $delivery_serie_art;
    public ?string $credit_note_serie;
    public ?string $credit_note_correlative;
    public ?string $delivery_date;
    public ?string $dispatch_note_serie;
    public ?string $dispatch_note_correlative;
    public ?string $dispatch_note_date;

    public function __construct(
        int $id,
        int $document_type_warranty_id,
        Company $company,
        Branch $branch,
        Branch $branch_sale,
        string $serie,
        string $correlative,
        Article $article,
        ?string $serie_art,
        string $date,
        Sale $reference_sale,
        Customer $customer,
        ?string $customer_phone,
        ?string $customer_email,
        ?string $failure_description,
        ?string $observations,
        ?string $diagnosis,
        Customer $supplier,
        EntryGuide $entry_guide,
        ?string $contact,
        ?string $follow_up_diagnosis,
        ?string $follow_up_status,
        ?string $solution,
        WarrantyStatus $warranty_status,
        ?string $solution_date,
        ?string $delivery_description,
        ?string $delivery_serie_art,
        ?string $credit_note_serie,
        ?string $credit_note_correlative,
        ?string $delivery_date,
        ?string $dispatch_note_serie,
        ?string $dispatch_note_correlative,
        ?string $dispatch_note_date
    ) {
        $this->id = $id;
        $this->document_type_warranty_id = $document_type_warranty_id;
        $this->company = $company;
        $this->branch = $branch;
        $this->branch_sale = $branch_sale;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->article = $article;
        $this->serie_art = $serie_art;
        $this->date = $date;
        $this->reference_sale = $reference_sale;
        $this->customer = $customer;
        $this->customer_phone = $customer_phone;
        $this->customer_email = $customer_email;
        $this->failure_description = $failure_description;
        $this->observations = $observations;
        $this->diagnosis = $diagnosis;
        $this->supplier = $supplier;
        $this->entry_guide = $entry_guide;
        $this->contact = $contact;
        $this->follow_up_diagnosis = $follow_up_diagnosis;
        $this->follow_up_status = $follow_up_status;
        $this->solution = $solution;
        $this->warranty_status = $warranty_status;
        $this->solution_date = $solution_date;
        $this->delivery_description = $delivery_description;
        $this->delivery_serie_art = $delivery_serie_art;
        $this->credit_note_serie = $credit_note_serie;
        $this->credit_note_correlative = $credit_note_correlative;
        $this->delivery_date = $delivery_date;
        $this->dispatch_note_serie = $dispatch_note_serie;
        $this->dispatch_note_correlative = $dispatch_note_correlative;
        $this->dispatch_note_date = $dispatch_note_date;
    }

    public function getId(): int {return $this->id;}
    public function getDocumentTypeWarrantyId(): int {return $this->document_type_warranty_id;}
    public function getCompany(): Company {return $this->company;}
    public function getBranch(): Branch {return $this->branch;}
    public function getBranchSale(): Branch {return $this->branch_sale;}
    public function getSerie(): string {return $this->serie;}
    public function getCorrelative(): string {return $this->correlative;}
    public function getArticle(): Article {return $this->article;}
    public function getSerieArt(): ?string {return $this->serie_art;}
    public function getDate(): string {return $this->date;}
    public function getReferenceSale(): Sale {return $this->reference_sale;}
    public function getCustomer(): Customer {return $this->customer;}
    public function getCustomerPhone(): ?string {return $this->customer_phone;}
    public function getCustomerEmail(): ?string {return $this->customer_email;}
    public function getFailureDescription(): ?string {return $this->failure_description;}
    public function getObservations(): ?string {return $this->observations;}
    public function getDiagnosis(): ?string {return $this->diagnosis;}
    public function getSupplier(): Customer {return $this->supplier;}
    public function getEntryGuide(): EntryGuide {return $this->entry_guide;}
    public function getContact(): ?string {return $this->contact;}
    public function getFollowUpDiagnosis(): ?string {return $this->follow_up_diagnosis;}
    public function getFollowUpStatus(): ?string {return $this->follow_up_status;}
    public function getSolution(): ?string {return $this->solution;}
    public function getWarrantyStatus(): WarrantyStatus {return $this->warranty_status;}
    public function getSolutionDate(): ?string {return $this->solution_date;}
    public function getDeliveryDescription(): ?string {return $this->delivery_description;}
    public function getDeliverySerieArt(): ?string {return $this->delivery_serie_art;}
    public function getCreditNoteSerie(): ?string {return $this->credit_note_serie;}
    public function getCreditNoteCorrelative(): ?string {return $this->credit_note_correlative;}
    public function getDeliveryDate(): ?string {return $this->delivery_date;}
    public function getDispatchNoteSerie(): ?string {return $this->dispatch_note_serie;}
    public function getDispatchNoteCorrelative(): ?string {return $this->dispatch_note_correlative;}
    public function getDispatchNoteDate(): ?string {return $this->dispatch_note_date;}
}
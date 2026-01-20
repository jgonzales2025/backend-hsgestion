<?php

namespace App\Modules\Warranty\Domain\Entities;

class UpdateWarranty
{
    public ?string $customer_email;
    public ?string $failure_description;
    public ?string $observations;
    public ?string $diagnosis;
    public ?string $follow_up_diagnosis;
    public ?string $follow_up_status;
    public ?string $solution;
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
        ?string $customer_email,
        ?string $failure_description,
        ?string $observations,
        ?string $diagnosis,
        ?string $follow_up_diagnosis,
        ?string $follow_up_status,
        ?string $solution,
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
        $this->customer_email = $customer_email;
        $this->failure_description = $failure_description;
        $this->observations = $observations;
        $this->diagnosis = $diagnosis;
        $this->follow_up_diagnosis = $follow_up_diagnosis;
        $this->follow_up_status = $follow_up_status;
        $this->solution = $solution;
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
    
    public function getCustomerEmail(): ?string { return $this->customer_email; }
    public function getFailureDescription(): ?string { return $this->failure_description; }
    public function getObservations(): ?string { return $this->observations; }
    public function getDiagnosis(): ?string { return $this->diagnosis; }
    public function getFollowUpDiagnosis(): ?string { return $this->follow_up_diagnosis; }
    public function getFollowUpStatus(): ?string { return $this->follow_up_status; }
    public function getSolution(): ?string { return $this->solution; }
    public function getSolutionDate(): ?string { return $this->solution_date; }
    public function getDeliveryDescription(): ?string { return $this->delivery_description; }
    public function getDeliverySerieArt(): ?string { return $this->delivery_serie_art; }
    public function getCreditNoteSerie(): ?string { return $this->credit_note_serie; }
    public function getCreditNoteCorrelative(): ?string { return $this->credit_note_correlative; }
    public function getDeliveryDate(): ?string { return $this->delivery_date; }
    public function getDispatchNoteSerie(): ?string { return $this->dispatch_note_serie; }
    public function getDispatchNoteCorrelative(): ?string { return $this->dispatch_note_correlative; }
    public function getDispatchNoteDate(): ?string { return $this->dispatch_note_date; }
}
<?php

namespace App\Modules\DispatchArticleSerial\Domain\Entities;

class DispatchArticleSerial
{
    private int $id;
    private int $dispatch_note_id;
    private int $article_id;
    private string $serial;
    private ?int $status;
    private ?int $origin_branch_id;
    private ?int $destination_branch_id;

    public function __construct(
        int $id,
        int $dispatch_note_id,
        int $article_id,
        string $serial,
        ?int $status,
        ?int $origin_branch_id,
        ?int $destination_branch_id
    ) {
        $this->id = $id;
        $this->dispatch_note_id = $dispatch_note_id;
        $this->article_id = $article_id;
        $this->serial = $serial;
        $this->status = $status;
        $this->origin_branch_id = $origin_branch_id;
        $this->destination_branch_id = $destination_branch_id;
    }

    public function getId(): int { return $this->id; }
    public function getDispatchNoteId(): int { return $this->dispatch_note_id; }
    public function getArticleId(): int { return $this->article_id; }
    public function getSerial(): string { return $this->serial; }
    public function getStatus(): ?int { return $this->status; }
    public function getOriginBranchId(): ?int { return $this->origin_branch_id; }
    public function getDestinationBranchId(): ?int { return $this->destination_branch_id; }
}
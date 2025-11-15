<?php

namespace App\Modules\DispatchArticleSerial\Application\DTOs;

class DispatchArticleSerialDTO
{
    public int $dispatchNoteId;
    public int $articleId;
    public string $serial;
    public int $status;
    public int $originBranchId;
    public ?int $destinationBranchId;

    public function __construct(array $data)
    {
        $this->dispatchNoteId = $data['dispatch_note_id'];
        $this->articleId = $data['article_id'];
        $this->serial = $data['serial'];
        $this->status = $data['status'];
        $this->originBranchId = $data['origin_branch_id'];
        $this->destinationBranchId = $data['destination_branch_id'] ?? null;
    }
}

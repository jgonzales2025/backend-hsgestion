<?php

namespace App\Modules\DispatchArticleSerial\Application\DTOs;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Branch\Domain\Entities\Branch;

class DispatchArticleSerialDTO
{
    public int $dispatchNoteId;
    public int $articleId;
    public string $serial;
    public ?int $emissionReasonsId;
    public int $status;
    public Branch $originBranch;
    public ?Branch $destinationBranch;

    public function __construct(array $data)
    {
        $this->dispatchNoteId = $data['dispatch_note_id'];
        $this->articleId = $data['article_id'];
        $this->serial = $data['serial'];
        $this->emissionReasonsId = $data['emission_reasons_id'] ?? null;
        $this->status = $data['status'];
        $this->originBranch = $data['origin_branch'];
        $this->destinationBranch = $data['destination_branch'] ?? null;
    }
}

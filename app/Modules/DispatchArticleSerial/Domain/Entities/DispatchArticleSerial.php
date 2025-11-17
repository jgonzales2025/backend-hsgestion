<?php

namespace App\Modules\DispatchArticleSerial\Domain\Entities;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Branch\Domain\Entities\Branch;

class DispatchArticleSerial
{
    private int $id;
    private int $dispatch_note_id;
    private Article $article;
    private string $serial;
    private ?int $emission_reasons_id;
    private ?int $status;
    private Branch $origin_branch;
    private ?Branch $destination_branch;

    public function __construct(
        int $id,
        int $dispatch_note_id,
        Article $article,
        string $serial,
        ?int $emission_reasons_id,
        ?int $status,
        Branch $origin_branch,
        ?Branch $destination_branch = null
    ) {
        $this->id = $id;
        $this->dispatch_note_id = $dispatch_note_id;
        $this->article = $article;
        $this->serial = $serial;
        $this->emission_reasons_id = $emission_reasons_id;
        $this->status = $status;
        $this->origin_branch = $origin_branch;
        $this->destination_branch = $destination_branch;
    }

    public function getId(): int { return $this->id; }
    public function getDispatchNoteId(): int { return $this->dispatch_note_id; }
    public function getArticle(): Article { return $this->article; }
    public function getSerial(): string { return $this->serial; }
    public function getEmissionReasonsId(): ?int { return $this->emission_reasons_id; }
    public function getStatus(): ?int { return $this->status; }
    public function getOriginBranch(): Branch { return $this->origin_branch; }
    public function getDestinationBranch(): ?Branch { return $this->destination_branch; }
}
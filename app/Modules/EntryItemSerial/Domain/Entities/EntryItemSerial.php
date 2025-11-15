<?php

namespace App\Modules\EntryItemSerial\Domain\Entities;


use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuideArticle\Domain\Entities\EntryGuideArticle;

class EntryItemSerial{
    private ?int $id;
    private EntryGuide $entry_guide;
    private EntryGuideArticle $article;
    private string $serial;
    private int $branch_id;
    private ?int $status;

    public function __construct(
        ?int $id,
        EntryGuide $entry_guide,
        EntryGuideArticle $article,
        string $serial,
        int $branch_id,
        ?int $status = 0
    ){
       $this->id = $id;
       $this->entry_guide = $entry_guide;
       $this->article = $article;
       $this->serial = $serial;
       $this->branch_id = $branch_id;
       $this->status = $status;
    }
    public function getId():int|null{
        return $this->id;
    }
    public function getEntryGuide():EntryGuide{
        return $this->entry_guide;
    }
    public function getEntryGuideArticle():EntryGuideArticle{
        return $this->article;
    }
    public function getSerial():string{
        return $this->serial;
    }
    public function getBranchId():int{
        return $this->branch_id;
    }
    public function getStatus():int|null{
        return $this->status;
    }
}

<?php

namespace App\Modules\EntryItemSerial\Application\DTOS;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;

class EntryItemSerialDTO{
    public EntryGuide $entry_guide;
    public Article $article;
    public string $serial;
    public int $branch_id;

    public function __construct($array){
        $this->entry_guide = $array['entry_guide'];
        $this->article = $array['article'];
        $this->serial = $array['serial'];
        $this->branch_id = $array['branch_id'];
    }

}

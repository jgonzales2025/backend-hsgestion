<?php

namespace App\Modules\EntryGuideArticle\Application\DTOS;

class EntryGuideArticleDTO
{
    public $entry_guide_id;
    public  $article_id;
    public  $description;
    public  $quantity;
    public  $saldo;

    function __construct($array)
    {
        $this->entry_guide_id = $array['entry_guide_id'];
        $this->article_id = $array['article_id'];
        $this->description = $array['description'];
        $this->quantity = $array['quantity'];
        $this->saldo = $array['saldo'] ?? $array['quantity'];
    }
}

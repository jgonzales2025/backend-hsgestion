<?php

namespace App\Modules\EntryGuideArticle\Application\DTOS;

class EntryGuideArticleDTO
{
    public $entry_guide_id;
    public  $article_id;
    public  $description;
    public  $quantity;
    public  $saldo;
    public  $subtotal;
    public  $total;
    public  $total_descuento;
    public  $descuento;

    function __construct($array)
    {
        $this->entry_guide_id = $array['entry_guide_id'];
        $this->article_id = $array['article_id'];
        $this->description = $array['description'];
        $this->quantity = $array['quantity'];
        $this->saldo = $array['saldo'] ?? $array['quantity'];
        $this->subtotal = $array['subtotal'] ?? 0.0;
        $this->total = $array['total'] ?? 0.0;
        $this->total_descuento = $array['precio_costo'] ?? 0.0;
        $this->descuento = $array['descuento'] ?? 0.0;
    }
}

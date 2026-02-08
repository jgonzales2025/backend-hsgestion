<?php

namespace App\Modules\VisibleArticles\Application\DTOS;

class VisibleArticleDTO
{

    public $company_id;
    public $branch_id;
    public $article_id;
    public $user_id;
    public $status;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->article_id = $data['article_id'];
        $this->user_id = $data['user_id'];
        $this->status = $data['status'];
    }
}
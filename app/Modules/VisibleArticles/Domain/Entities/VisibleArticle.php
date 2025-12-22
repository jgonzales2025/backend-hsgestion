<?php

namespace App\Modules\VisibleArticles\Domain\Entities;

class VisibleArticle
{
  private ?int $id;
  private ?int $company_id;
  private ?int $branch_id;
  private ?int $article_id;
  private ?int $user_id;
  private bool $status;

  public function __construct(
    int $id,
    ?int $company_id,
    ?int $branch_id,
    ?int $article_id,
    ?int $user_id,
    bool $status
  ) {
    $this->id = $id;
    $this->company_id = $company_id;
    $this->branch_id = $branch_id;
    $this->article_id = $article_id;
    $this->user_id = $user_id;
    $this->status = $status;
  }
  public function getId(): int|null
  {
    return $this->id;
  }
  public function getCompany_id(): int|null
  {
    return $this->company_id;
  }
  public function getBranch_id(): int|null
  {
    return $this->branch_id;
  }
  public function getArticle_id(): int|null
  {
    return $this->article_id;
  }
  public function getUser_id(): int|null
  {
    return $this->user_id;
  }
  public function getStatus(): bool
  {
    return $this->status;
  }
}

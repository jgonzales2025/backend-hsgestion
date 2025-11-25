<?php

namespace App\Modules\VisibleArticles\Infrastructure\Persistence;

use App\Modules\VisibleArticles\Domain\Entities\VisibleArticle;
use App\Modules\VisibleArticles\Domain\Interfaces\VisibleArticleRepositoryInterface;
use App\Modules\VisibleArticles\Infrastructure\Models\EloquentVisibleArticle;

class EloquentVisibleArticleRepository implements VisibleArticleRepositoryInterface
{
    public function findById(int $id): ?VisibleArticle
    {
        $visible = EloquentVisibleArticle::find($id);

        if (!$visible) {
            return null;
        }
        return new VisibleArticle(
            id: $visible->id,
            company_id: $visible->company_id,
            branch_id: $visible->branch_id,
            article_id: $visible->article_id,
            user_id: $visible->user_id,
            status: $visible->status,
        );

    }
    public function update(VisibleArticle $visibleArticle): void
    {
        $visible = EloquentVisibleArticle::find($visibleArticle->getId());

        $visible->update([
            'status' => $visibleArticle->getStatus()
        ]);
    }
    public function mostrar(int $id): array
    {
        $companyId = request()->get('company_id');
        
        $visibleArticles = EloquentVisibleArticle::where('article_id', $id)
            ->where('company_id', $companyId)
            ->get();

        return $visibleArticles->map(function ($visible) {
            return new VisibleArticle(
                id: $visible->id,
                company_id: $visible->company_id,
                branch_id: $visible->branch_id,
                article_id: $visible->article_id,
                user_id: $visible->user_id,
                status: $visible->status
            );
        })->toArray();
    }

    public function findStatusByArticleId(int $articleId, int $branchId): ?int
    {
        $visible = EloquentVisibleArticle::where('article_id', $articleId)
            ->where('branch_id', $branchId)
            ->first();

        if (!$visible) {
            return null;
        }
        
        return $visible->status;
    }

}
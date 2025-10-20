<?php

namespace App\Modules\VisibleArticles\Infrastructure\Persistence;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\VisibleArticles\Domain\Entities\VisibleArticle;
use App\Modules\VisibleArticles\Domain\Interfaces\VisibleArticleRepositoryInterface;
use App\Modules\VisibleArticles\Infrastructure\Models\EloquentVisibleArticle;
use Illuminate\Support\Facades\Log;

class EloquentVisibleArticleRepository implements VisibleArticleRepositoryInterface
{
    public function findById(int $id): ?VisibleArticle
    {
        $visible = EloquentVisibleArticle::find($id);
        // Log::info('eloquentArticle', $visible->toArray());

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

    $visibleArticles = EloquentVisibleArticle::where('article_id', $id)->get();


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


}
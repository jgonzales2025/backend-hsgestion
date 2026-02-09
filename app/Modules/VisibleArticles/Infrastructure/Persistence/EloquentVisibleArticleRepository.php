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
        if ($visibleArticle->getId() == 0) {
            EloquentVisibleArticle::create([
                'company_id' => $visibleArticle->getCompany_id(),
                'branch_id' => $visibleArticle->getBranch_id(),
                'article_id' => $visibleArticle->getArticle_id(),
                'user_id' => $visibleArticle->getUser_id(),
                'status' => $visibleArticle->getStatus()
            ]);
            return;
        }

        $visible = EloquentVisibleArticle::find($visibleArticle->getId());

        $visible->update([
            'status' => $visibleArticle->getStatus()
        ]);
    }

    public function mostrar(int $id): array
    {
        $companyId = request()->get('company_id');
        
        // Obtener todas las sucursales de la compañía
        $allBranches = \App\Modules\Branch\Infrastructure\Models\EloquentBranch::where('cia_id', $companyId)
            ->where('status', 1)
            ->get();
        
        // Obtener los artículos visibles existentes para este artículo
        $visibleArticles = EloquentVisibleArticle::where('article_id', $id)
            ->where('company_id', $companyId)
            ->get()
            ->keyBy('branch_id');
        
        // Crear un registro para cada sucursal, indicando si está visible o no
        return $allBranches->map(function ($branch) use ($visibleArticles, $id, $companyId) {
            $visible = $visibleArticles->get($branch->id);
            
            return new VisibleArticle(
                id: $visible ? $visible->id : null,
                company_id: $companyId,
                branch_id: $branch->id,
                article_id: $id,
                user_id: $visible ? $visible->user_id : null,
                status: $visible ? $visible->status : false
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

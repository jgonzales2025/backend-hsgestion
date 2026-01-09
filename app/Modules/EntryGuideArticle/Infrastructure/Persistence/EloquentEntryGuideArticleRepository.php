<?php

namespace App\Modules\EntryGuideArticle\Infrastructure\Persistence;

use App\Modules\EntryGuideArticle\Domain\Entities\EntryGuideArticle;
use App\Modules\EntryGuideArticle\Domain\Interface\EntryGuideArticleRepositoryInterface;
use App\Modules\EntryGuideArticle\Infrastructure\Models\EloquentEntryGuideArticle;

class EloquentEntryGuideArticleRepository implements EntryGuideArticleRepositoryInterface
{

    public function save(EntryGuideArticle $entryGuideArticle): ?EntryGuideArticle
    {
        $eloquentEntryGuideArticle = EloquentEntryGuideArticle::create([

            'entry_guide_id' => $entryGuideArticle->getEntryGuideId(),
            'article_id' => $entryGuideArticle->getArticle()->getId(),
            'description' => $entryGuideArticle->getDescription(),
            'quantity' => $entryGuideArticle->getQuantity(),
            'saldo' => $entryGuideArticle->getQuantity(),
            'subtotal' => $entryGuideArticle->getSubtotal(),
            'total' => $entryGuideArticle->getTotal(),
            'total_descuento' => $entryGuideArticle->getTotalDescuento(),
            'descuento' => $entryGuideArticle->getDescuento(),

        ]);
        return new EntryGuideArticle(
            id: $eloquentEntryGuideArticle->id,
            entry_guide_id: $eloquentEntryGuideArticle->entry_guide_id,
            article: $entryGuideArticle->getArticle(),
            description: $eloquentEntryGuideArticle->description,
            quantity: $eloquentEntryGuideArticle->quantity,
            saldo: $eloquentEntryGuideArticle->saldo,
            subtotal: $eloquentEntryGuideArticle->subtotal,
            total: $eloquentEntryGuideArticle->total,
            total_descuento: $eloquentEntryGuideArticle->total_descuento,
            descuento: $eloquentEntryGuideArticle->descuento,
        );
    }
    public function findAll(): array
    {
        return [];
    }
    public function findById(int $id): array
    {
        $eloquentEntryGuideArticle = EloquentEntryGuideArticle::where('entry_guide_id', $id)->get();
        return $eloquentEntryGuideArticle->map(function ($entryGuideArticle) {
            return new EntryGuideArticle(
                id: $entryGuideArticle->id,
                entry_guide_id: $entryGuideArticle->entry_guide_id,
                article: $entryGuideArticle->article->toDomain($entryGuideArticle->article),
                description: $entryGuideArticle->description,
                quantity: $entryGuideArticle->quantity,
                saldo: $entryGuideArticle->saldo,
                subtotal: $entryGuideArticle->subtotal,
                total: $entryGuideArticle->total,
                total_descuento: $entryGuideArticle->total_descuento,
                descuento: $entryGuideArticle->descuento,
            );
        })->toArray();
    }
    public function findByIdObj(int $entryGuideId, int $articleId): ?EntryGuideArticle
    {

        $eloquent = EloquentEntryGuideArticle::where('entry_guide_id', $entryGuideId)
            ->where('article_id', $articleId)
            ->first();

        if (!$eloquent) {
            return null;
        }

        return new EntryGuideArticle(
            id: $eloquent->id,
            entry_guide_id: $eloquent->entry_guide_id,
            article: $eloquent->article?->toDomain($eloquent->article),
            description: $eloquent->description,
            quantity: $eloquent->quantity,
            saldo: $eloquent->saldo,
            subtotal: $eloquent->subtotal,
            total: $eloquent->total,
            total_descuento: $eloquent->total_descuento,
            descuento: $eloquent->descuento,
        );
    }


    public function update(EntryGuideArticle $article): void
    {
        EloquentEntryGuideArticle::where('id', $article->getId())->update([
            'quantity' => $article->getQuantity(),
            'saldo' => $article->getSaldo(),
            'subtotal' => $article->getSubtotal(),
            'total' => $article->getTotal(),
            'total_descuento' => $article->getTotalDescuento(),
            'descuento' => $article->getDescuento(),
        ]);
    }
    public function updateQuantity(int $articleId, int $quantity): void
    {
        EloquentEntryGuideArticle::where('article_id', $articleId)->update([
            'quantity' => $quantity,
            'saldo' => $quantity,
        ]);
    }
    public function deleteByEntryGuideId(int $id): void
    {
        EloquentEntryGuideArticle::where('entry_guide_id', $id)->delete();
    }
}

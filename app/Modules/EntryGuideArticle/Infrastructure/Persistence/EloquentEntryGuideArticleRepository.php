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
            'saldo' => $entryGuideArticle->getSaldo(),

        ]);
        return new EntryGuideArticle(
            id: $eloquentEntryGuideArticle->id,
            entry_guide_id: $eloquentEntryGuideArticle->entry_guide_id,
            article: $entryGuideArticle->getArticle(),
            description: $eloquentEntryGuideArticle->description,
            quantity: $eloquentEntryGuideArticle->quantity,
            saldo: $eloquentEntryGuideArticle->saldo,
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
                saldo: (float) $entryGuideArticle->saldo
            );
        })->toArray();
    }

    public function deleteByEntryGuideId(int $id): void
    {
        EloquentEntryGuideArticle::where('entry_guide_id', $id)->delete();
    }
}

<?php

namespace App\Modules\EntryItemSerial\Infrastructure\Persistence;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\EntryItemSerial\Domain\Entities\EntryItemSerial;
use App\Modules\EntryItemSerial\Infrastructure\Models\EloquentEntryItemSerial;

class EloquentEntryItemSerialRepository implements EntryItemSerialRepositoryInterface{

    public function save(EntryItemSerial $entryItemSerial):?EntryItemSerial{

         $eloquentEntryItemSerial = EloquentEntryItemSerial::create([
            'entry_guide_id' => $entryItemSerial->getEntryGuide()->getId(),
            'article_id' => $entryItemSerial->getArticle()->getId(),
            'serial' => $entryItemSerial->getSerial(),
            'branch_id' => $entryItemSerial->getBranchId(),
        ]);

        return new EntryItemSerial(
            id: $eloquentEntryItemSerial->id,
            entry_guide: $entryItemSerial->getEntryGuide(),
            article: $entryItemSerial->getArticle(),
            serial: $eloquentEntryItemSerial->serial,
            branch_id: $eloquentEntryItemSerial->branch_id,
        );
    }

    public function findById(int $id):array{
        $eloquentFindById = EloquentEntryItemSerial::where('entry_guide_id',$id)->get();
        if (!$eloquentFindById) {
            return [];
        }
      return  $eloquentFindById->map(function ($entryItemSerial) {
        return new EntryItemSerial(
            id: $entryItemSerial->id,
            entry_guide: $entryItemSerial->entry_guide_id,
            article: $entryItemSerial->article_id,
            serial: $entryItemSerial->serial,
            branch_id: $entryItemSerial->branch_id,
        );
    })->toArray();
    }

    public function deleteByIdEntryItemSerial(int $id):void{
        EloquentEntryItemSerial::where('entry_guide_id',$id)->delete();

    }

    public function findSerialsByEntryGuideId(int $entryGuideId): array
    {
        $rows = EloquentEntryItemSerial::where('entry_guide_id', $entryGuideId)->get(['article_id', 'serial']);
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row->article_id][] = $row->serial;
        }
        return $grouped;
    }

    public function findBySerial(string $serial): ?EntryItemSerial
    {
        $entryItemSerial = EloquentEntryItemSerial::where('serial', $serial)->first();
        if (!$entryItemSerial) {
            return null;
        }
        return new EntryItemSerial(
            id: $entryItemSerial->id,
            entry_guide: $entryItemSerial->entryGuide->toDomain($entryItemSerial->entryGuide),
            article: $entryItemSerial->article->toDomain($entryItemSerial->article),
            serial: $entryItemSerial->serial,   
            branch_id: $entryItemSerial->branch_id,
        );
    }

    /**
     * Buscar seriales por id de articulo
     * @param int $articleId
     * @param int $branch_id
     * @param mixed $updated
     * @param mixed $serial
     * @return array|null
     */
    public function findSerialByArticleId(int $articleId, int $branch_id, ?bool $updated, ?string $serial = null): ?array
    {
        $rows = EloquentEntryItemSerial::where('article_id', $articleId)
            ->where('branch_id', $branch_id)
            ->when($updated === null || $updated === false, function ($query) {
                return $query->where('status', 1);
            })
            ->when($serial, function ($query, $serial) {
                return $query->where('serial', 'like', "%{$serial}%");
            })
            ->get(['serial']);

        if (!$rows) {
            return null;
        }

        return $rows->pluck('serial')->toArray();
    }

    /**
     * *Buscar sucursal por serial de articulo
     * @param string $serial
     * @return array{branch_id: mixed, name: mixed|null}
     */
    public function findBranchBySerial(string $serial): ?array
    {
        $entryItemSerial = EloquentEntryItemSerial::where('serial', $serial)->first();
        if (!$entryItemSerial) {
            return null;
        }
        $branch = EloquentBranch::where('id', $entryItemSerial->branch_id)->first();
        return [
            'branch_id' => $branch->id,
            'name' => $branch->name,
        ];
    }

}











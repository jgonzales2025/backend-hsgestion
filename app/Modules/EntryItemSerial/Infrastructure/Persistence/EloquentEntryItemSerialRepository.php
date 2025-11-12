<?php

namespace App\Modules\EntryItemSerial\Infrastructure\Persistence;

use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\EntryItemSerial\Domain\Entities\EntryItemSerial;
use App\Modules\EntryItemSerial\Infrastructure\Models\EloquentEntryItemSerial;

class EloquentEntryItemSerialRepository implements EntryItemSerialRepositoryInterface{

    public function save(EntryItemSerial $entryItemSerial):?EntryItemSerial{

         $eloquentEntryItemSerial = EloquentEntryItemSerial::create([
            'entry_guide_id' => $entryItemSerial->getEntryGuide()->getId(),
            'article_id' => $entryItemSerial->getEntryGuideArticle()->getArticle()->getId(),
            'serial' => $entryItemSerial->getSerial(),
        ]);

        return new EntryItemSerial(
            id: $eloquentEntryItemSerial->id,
            entry_guide: $entryItemSerial->getEntryGuide(),
            article: $entryItemSerial->getEntryGuideArticle(),
            serial: $eloquentEntryItemSerial->serial
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

}

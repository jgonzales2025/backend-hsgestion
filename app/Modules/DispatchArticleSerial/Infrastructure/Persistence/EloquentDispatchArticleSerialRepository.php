<?php

namespace App\Modules\DispatchArticleSerial\Infrastructure\Persistence;

use App\Modules\DispatchArticleSerial\Domain\Entities\DispatchArticleSerial;
use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;
use App\Modules\DispatchArticleSerial\Infrastructure\Models\EloquentDispatchArticleSerial;
use App\Modules\EntryItemSerial\Infrastructure\Models\EloquentEntryItemSerial;
use Illuminate\Database\Eloquent\Collection;

class EloquentDispatchArticleSerialRepository implements DispatchArticleSerialRepositoryInterface
{
    public function save(DispatchArticleSerial $dispatchArticleSerial): ?DispatchArticleSerial
    {
        $eloquentDispatchArticleSerial = EloquentDispatchArticleSerial::create([
            'dispatch_note_id' => $dispatchArticleSerial->getDispatchNoteId(),
            'article_id' => $dispatchArticleSerial->getArticleId(),
            'serial' => $dispatchArticleSerial->getSerial(),
            'status' => $dispatchArticleSerial->getStatus(),
            'origin_branch_id' => $dispatchArticleSerial->getOriginBranchId(),
            'destination_branch_id' => $dispatchArticleSerial->getDestinationBranchId(),
        ]);

        if ($eloquentDispatchArticleSerial->status == 2)
        {
            EloquentEntryItemSerial::where('serial', $eloquentDispatchArticleSerial->serial)->update(['status' => 2]);
        }

        return new DispatchArticleSerial(
            $eloquentDispatchArticleSerial->id,
            $dispatchArticleSerial->getDispatchNoteId(),
            $dispatchArticleSerial->getArticleId(),
            $dispatchArticleSerial->getSerial(),
            $dispatchArticleSerial->getStatus(),
            $dispatchArticleSerial->getOriginBranchId(),
            $dispatchArticleSerial->getDestinationBranchId(),
        );
    }


}

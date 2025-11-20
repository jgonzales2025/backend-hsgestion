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
            'dispatch_note_id' => $dispatchArticleSerial->getDispatchNote()->getId(),
            'article_id' => $dispatchArticleSerial->getArticle()->getId(),
            'serial' => $dispatchArticleSerial->getSerial(),
            'emission_reasons_id' => $dispatchArticleSerial->getEmissionReasonsId(),
            'status' => $dispatchArticleSerial->getStatus(),
            'origin_branch_id' => $dispatchArticleSerial->getOriginBranch()->getId(),
            'destination_branch_id' => $dispatchArticleSerial->getDestinationBranch()?->getId(),
        ]);

        if ($eloquentDispatchArticleSerial->status == 2)
        {
            EloquentEntryItemSerial::where('serial', $eloquentDispatchArticleSerial->serial)->update(['status' => 2]);
        }

        return new DispatchArticleSerial(
            $eloquentDispatchArticleSerial->id,
            $dispatchArticleSerial->getDispatchNote(),
            $dispatchArticleSerial->getArticle(),
            $dispatchArticleSerial->getSerial(),
            $dispatchArticleSerial->getEmissionReasonsId(),
            $dispatchArticleSerial->getStatus(),
            $dispatchArticleSerial->getOriginBranch(),
            $dispatchArticleSerial->getDestinationBranch(),
        );
    }

    public function findAllTransferMovements(int $branchId): array
    {
        $dispatchArticleSerials = EloquentDispatchArticleSerial::where('origin_branch_id', $branchId)
            ->orWhere('destination_branch_id', $branchId)
            ->get();

        return $dispatchArticleSerials->map(function ($dispatchArticleSerial) {
            return new DispatchArticleSerial(
                $dispatchArticleSerial->id,
                $dispatchArticleSerial->dispatch_note_id,
                $dispatchArticleSerial->article->toDomain($dispatchArticleSerial->article),
                $dispatchArticleSerial->serial,
                $dispatchArticleSerial->emission_reasons_id,
                $dispatchArticleSerial->status,
                $dispatchArticleSerial->originBranch->toDomain($dispatchArticleSerial->originBranch),
                $dispatchArticleSerial->destinationBranch->toDomain($dispatchArticleSerial->destinationBranch),
            );
        })->toArray();
    }

    public function findSerialsByTransferOrderId(int $transferOrderId): array
    {
        $rows = EloquentDispatchArticleSerial::where('dispatch_note_id', $transferOrderId)->get();

        return $rows
            ->groupBy('article_id')
            ->map(function ($items) {
                return $items->pluck('serial')->values()->toArray();
            })
            ->toArray();
    }

}

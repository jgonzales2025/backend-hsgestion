<?php

namespace App\Modules\BuildDetailPc\Infrastructure\Persistence;

use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;
use App\Modules\BuildDetailPc\Infrastructure\Models\EloquentBuildDetailPc;
use App\Modules\BuildDetailPc\Domain\Entities\BuildDetailPc;

class EloquentBuildDetaiPcRepository implements BuildDetailPcRepositoryInterface
{
    public function findAll(): array
    {
        $buildDetailPc =  EloquentBuildDetailPc::all();

        return $buildDetailPc->map(function ($buildDetailPc) {
            return new BuildDetailPc(
                id: $buildDetailPc->id,
                build_pc_id: $buildDetailPc->build_pc_id,
                article_id: $buildDetailPc->article_id,
                quantity: $buildDetailPc->quantity,
                price: $buildDetailPc->price,
                subtotal: $buildDetailPc->subtotal
            );
        })->toArray();
    }
    public function findById(int $id): ?BuildDetailPc
    {
        $buildDetailPc = EloquentBuildDetailPc::find($id);
        return new BuildDetailPc(
            id: $buildDetailPc->id,
            build_pc_id: $buildDetailPc->build_pc_id,
            article_id: $buildDetailPc->article_id,
            quantity: $buildDetailPc->quantity,
            price: $buildDetailPc->price,
            subtotal: $buildDetailPc->subtotal
        );
    }

    public function findByBuildPcId(int $buildPcId): array
    {
        $details = EloquentBuildDetailPc::where('build_pc_id', $buildPcId)->get();

        return $details->map(function ($detail) {
            return new BuildDetailPc(
                id: $detail->id,
                build_pc_id: $detail->build_pc_id,
                article_id: $detail->article_id,
                quantity: $detail->quantity,
                price: $detail->price,
                subtotal: $detail->subtotal
            );
        })->toArray();
    }

    public function create(BuildDetailPc $data): ?BuildDetailPc
    {
        $buildDetailPc = EloquentBuildDetailPc::create([
            'build_pc_id' => $data->getBuildPcId(),
            'article_id' => $data->getArticleId(),
            'quantity' => $data->getQuantity(),
            'price' => $data->getPrice(),
            'subtotal' => $data->getSubtotal(),
        ]);
        return new BuildDetailPc(
            id: $buildDetailPc->id,
            build_pc_id: $buildDetailPc->build_pc_id,
            article_id: $buildDetailPc->article_id,
            quantity: $buildDetailPc->quantity,
            price: $buildDetailPc->price,
            subtotal: $buildDetailPc->subtotal
        );
    }

    public function update(BuildDetailPc $data): ?BuildDetailPc
    {
        $buildDetailPc = EloquentBuildDetailPc::find($data->getId())->update([
            'build_pc_id' => $data->getBuildPcId(),
            'article_id' => $data->getArticleId(),
            'quantity' => $data->getQuantity(),
            'price' => $data->getPrice(),
            'subtotal' => $data->getSubtotal(),
        ]);
        return new BuildDetailPc(
            id: $buildDetailPc->id,
            build_pc_id: $buildDetailPc->build_pc_id,
            article_id: $buildDetailPc->article_id,
            quantity: $buildDetailPc->quantity,
            price: $buildDetailPc->price,
            subtotal: $buildDetailPc->subtotal
        );
    }

    public function deleteByBuildPcId(int $buildPcId): void
    {
        EloquentBuildDetailPc::where('build_pc_id', $buildPcId)->delete();
    }
}

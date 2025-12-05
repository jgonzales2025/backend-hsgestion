<?php

namespace App\Modules\DetVoucherPurchase\Infrastructure\Persistence;

use App\Modules\DetVoucherPurchase\application\DTOS\DetVoucherPurchaseDTO;
use App\Modules\DetVoucherPurchase\Domain\Entities\DetVoucherPurchase;
use App\Modules\DetVoucherPurchase\Domain\Interface\DetVoucherPurchaseRepositoryInterface;
use App\Modules\DetVoucherPurchase\Infrastructure\Models\EloquentDetVoucherPurchase;

class EloquentDetVoucherPurchaseRepository implements DetVoucherPurchaseRepositoryInterface
{
    public function create(DetVoucherPurchaseDTO $detVoucherPurchaseDTO): DetVoucherPurchase
    {
        $detVoucherPurchase = EloquentDetVoucherPurchase::create([
            'voucher_id' => $detVoucherPurchaseDTO->voucher_id,
            'purchase_id' => $detVoucherPurchaseDTO->purchase_id,
            'amount' => $detVoucherPurchaseDTO->amount,
        ]);
        return new DetVoucherPurchase(
            $detVoucherPurchase->id,
            $detVoucherPurchase->voucher_id,
            $detVoucherPurchase->purchase_id,
            $detVoucherPurchase->amount
        );
       
    }

    public function findById(int $id): DetVoucherPurchase
    {
        $eloquentDetVoucherPurchase = EloquentDetVoucherPurchase::find($id);
        return new DetVoucherPurchase(
            $eloquentDetVoucherPurchase->id,
            $eloquentDetVoucherPurchase->voucher_id,
            $eloquentDetVoucherPurchase->purchase_id,
            $eloquentDetVoucherPurchase->amount
        );
    }

    public function findAll(): array
    {
        $eloquentDetVoucherPurchases = EloquentDetVoucherPurchase::all();
        return $eloquentDetVoucherPurchases->map(function ($eloquentDetVoucherPurchase) {
            return new DetVoucherPurchase(
                $eloquentDetVoucherPurchase->id,
                $eloquentDetVoucherPurchase->voucher_id,
                $eloquentDetVoucherPurchase->purchase_id,
                $eloquentDetVoucherPurchase->amount
            );
        })->toArray();
        
    }

     public function findAllVoucher(int $id): array
    {
        $eloquentDetVoucherPurchases = EloquentDetVoucherPurchase::where('voucher_id',$id)->get();
        return $eloquentDetVoucherPurchases->map(function ($eloquentDetVoucherPurchase) {
            return new DetVoucherPurchase(
                $eloquentDetVoucherPurchase->id,
                $eloquentDetVoucherPurchase->voucher_id,
                $eloquentDetVoucherPurchase->purchase_id,
                $eloquentDetVoucherPurchase->amount
            );
        })->toArray();
        
    }
}
<?php
namespace App\Modules\PaymentType\Infrastructure\Persistence;

use App\Modules\PaymentType\Domain\Entities\PaymentType;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;

class EloquentPaymentTypeRepository implements PaymentTypeRepositoryInterface{
    public function findAllpaymentType():array{
          $paymentType = EloquentPaymentType::all();
          if ($paymentType->isEmpty()) {
             return [];
          }
          return $paymentType->map(function ($paymentType){
               return new PaymentType(
                 id:$paymentType->id,
                 name:$paymentType->name,
                 status:$paymentType->status
               );
          })->toArray();
    }
    public function findById(int $id):?PaymentType{
          $paymentType = EloquentPaymentType::find($id);
          if (!$paymentType) {
             return null;
          }
          return new PaymentType(
            id:$paymentType->id,
            name:$paymentType->name,
            status:$paymentType->status
          );

    }
}
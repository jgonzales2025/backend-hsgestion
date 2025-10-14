<?php
namespace App\Modules\PaymentType\Domain\Interfaces;

use App\Modules\PaymentType\Domain\Entities\PaymentType;

interface PaymentTypeRepositoryInterface{
      public function findAllpaymentType():array;
      public function findById(int $id):?PaymentType;
}
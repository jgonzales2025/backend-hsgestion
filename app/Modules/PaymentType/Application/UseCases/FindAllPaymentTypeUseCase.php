<?php
namespace App\Modules\PaymentType\Application\UseCases;

use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;

class FindAllPaymentTypeUseCase{
      private paymentTypeRepositoryInterface $paymentTypeRepository;
     
      public function __construct(PaymentTypeRepositoryInterface $paymentTypeRepository){
         $this->paymentTypeRepository = $paymentTypeRepository;
      }

      public function execute(){
        return $this->paymentTypeRepository->findAllpaymentType();
      }
}
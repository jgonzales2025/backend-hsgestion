<?php
namespace App\Modules\PaymentType\Application\UseCases;

use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;

class FindByIdPaymentTypeUseCase{
      private paymentTypeRepositoryInterface $paymentTypeRepository;
     
      public function __construct(PaymentTypeRepositoryInterface $paymentTypeRepository){
         $this->paymentTypeRepository = $paymentTypeRepository;
      }

      public function execute(int $id){
        return $this->paymentTypeRepository->findById($id);
      }
}
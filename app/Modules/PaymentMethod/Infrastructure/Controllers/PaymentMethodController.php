<?php

namespace App\Modules\PaymentMethod\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PaymentMethod\Application\UseCases\FindAllPaymentMethodsUseCase;
use App\Modules\PaymentMethod\Infrastructure\Persistence\EloquentPaymentMethodRepository;
use App\Modules\PaymentMethod\Infrastructure\Resources\PaymentMethodResource;

class PaymentMethodController extends Controller
{
    protected $paymentMethodRepository;

    public function __construct()
    {
        $this->paymentMethodRepository = new EloquentPaymentMethodRepository();
    }

    public function findAllPaymentMethods(): array
    {
        $paymentMethodUseCase = new FindAllPaymentMethodsUseCase($this->paymentMethodRepository);
        $paymentMethods = $paymentMethodUseCase->execute();

        return PaymentMethodResource::collection($paymentMethods)->resolve();
    }
}
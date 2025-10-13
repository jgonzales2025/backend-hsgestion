<?php
namespace App\Modules\PaymentType\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\PaymentType\Application\UseCases\FindAllPaymentTypeUseCase;
use App\Modules\PaymentType\Application\UseCases\FindByIdPaymentTypeUseCase;
use App\Modules\PaymentType\Domain\Entities\PaymentType;
use App\Modules\PaymentType\Infrastructure\Models\EloquentPaymentType;
use App\Modules\PaymentType\Infrastructure\Persistence\EloquentPaymentTypeRepository;
use App\Modules\PaymentType\Infrastructure\Resources\PaymentTypeResource;
use Illuminate\Http\JsonResponse;

class PaymentTypeController extends Controller{

    protected $paymentTypeRepository;

    public function __construct(){
        $this->paymentTypeRepository = new EloquentPaymentTypeRepository();
    }
    public function index():array{
     $paymentTypeUseCase = new FindAllPaymentTypeUseCase($this->paymentTypeRepository);
    $paymentType = $paymentTypeUseCase->execute(); 

    return PaymentTypeResource::collection($paymentType)->resolve();
    }
    public function show(int $id):JsonResponse{
         $paymentTypeUseCase = new FindByIdPaymentTypeUseCase($this->paymentTypeRepository);
         $paymentType = $paymentTypeUseCase->execute($id);

         return response()->json(
            (new PaymentTypeResource($paymentType))->resolve(),
            200
         );
    }
}
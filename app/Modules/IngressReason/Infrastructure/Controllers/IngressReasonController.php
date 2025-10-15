<?php

namespace App\Modules\IngressReason\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\IngressReason\Application\UseCases\FindAllIngressReasonUseCase;
use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;
use App\Modules\IngressReason\Infrastructure\Resources\IngressReasonResource;

class IngressReasonController extends Controller
{

    public function __construct(private readonly IngressReasonRepositoryInterface $ingressReasonRepository){}

    public function index(): array
    {
        $ingressReasonUseCase = new FindAllIngressReasonUseCase($this->ingressReasonRepository);
        $ingressReasons = $ingressReasonUseCase->execute();

        return IngressReasonResource::collection($ingressReasons)->resolve();
    }
}

<?php

namespace App\Modules\DispatchArticle\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DispatchArticle\Application\UseCase\FindAllDispatchArticleUseCase;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DispatchArticleController extends Controller{
     
    public function __construct(private readonly DispatchArticleRepositoryInterface $dispatchArticleRepositoryInterface){}

    public function index():array{
        $dispatchArticleUseCase = new FindAllDispatchArticleUseCase($this->dispatchArticleRepositoryInterface);
        $dispatchArticle = $dispatchArticleUseCase->execute();
        Log::info("dispatchArticle",$dispatchArticle);

       return DispatchArticleResource::collection($dispatchArticle)->resolve();

    }
}
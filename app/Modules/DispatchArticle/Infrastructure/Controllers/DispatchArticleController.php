<?php

namespace App\Modules\DispatchArticle\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DispatchArticle\Application\UseCase\FindAllDispatchArticleUseCase;
use App\Modules\DispatchArticle\Application\UseCase\FindByIdDispatchArticle;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use Illuminate\Http\JsonResponse;

class DispatchArticleController extends Controller
{

  public function __construct(private readonly DispatchArticleRepositoryInterface $dispatchArticleRepositoryInterface)
  {
  }

  public function index(): array
  {
    $dispatchArticleUseCase = new FindAllDispatchArticleUseCase($this->dispatchArticleRepositoryInterface);
    $dispatchArticle = $dispatchArticleUseCase->execute();

    return DispatchArticleResource::collection($dispatchArticle)->resolve();

  }
  public function show($id): JsonResponse
  {
    $dispatchArticleUseCase = new FindByIdDispatchArticle($this->dispatchArticleRepositoryInterface);
    $dispatchArticle = $dispatchArticleUseCase->execute($id);

    return response()->json(
      (new DispatchArticleResource($dispatchArticle))->resolve(),
      200
    );

  }
}
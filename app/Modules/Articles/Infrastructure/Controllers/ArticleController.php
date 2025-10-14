<?php

namespace App\Modules\Articles\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Application\DTOs\ArticleDTO;
use App\Modules\Articles\Application\UseCases\CreateArticleUseCase;
use App\Modules\Articles\Application\UseCases\FindAllArticleUseCase;
use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Application\UseCases\UpdateArticleUseCase;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\Articles\Infrastructure\Requests\StoreArticleRequest;
use App\Modules\Articles\Infrastructure\Requests\UpdateArticleRequest;
use App\Modules\Articles\Infrastructure\Resource\ArticleResource;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller{

    protected $articleRepository;
     public function __construct(){
        $this->articleRepository = new EloquentArticleRepository();
     }
     public function index():array{
        $articleUseCase = new FindAllArticleUseCase($this->articleRepository);
        $article = $articleUseCase->execute();

        return ArticleResource::collection($article)->resolve();
     }
          public function show(int $id):JsonResponse{
        $articleUseCase = new FindByIdArticleUseCase($this->articleRepository);
        $article = $articleUseCase->execute($id);

        return response()->json(
            (new ArticleResource($article))->resolve(),
            200
        );

     }
          public function update(UpdateArticleRequest $request,int $id):JsonResponse{
        $articleDTO = new ArticleDTO($request->validated());
       
            $articleUseCase = new UpdateArticleUseCase($this->articleRepository);
        $article = $articleUseCase->execute($id,$articleDTO);

      return response()->json(
        (new ArticleResource($article))->resolve(),
        200
      );
     }
          public function store(StoreArticleRequest $request):JsonResponse{
        $articleDTO = new ArticleDTO($request->validated());
        $articleUseCase = new CreateArticleUseCase($this->articleRepository);
        $article = $articleUseCase->execute($articleDTO);

        return response()->json(
            (new ArticleResource($article))->resolve(),
            201
        );

           
     }
}